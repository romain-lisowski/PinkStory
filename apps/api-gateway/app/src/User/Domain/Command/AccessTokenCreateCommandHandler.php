<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Validator\ConstraintViolation;
use App\Common\Domain\Validator\ValidationFailedException;
use App\Common\Domain\Validator\ValidatorInterface;
use App\User\Domain\Event\AccessTokenCreatedEvent;
use App\User\Domain\Model\AccessToken;
use App\User\Domain\Repository\AccessTokenRepositoryInterface;
use App\User\Domain\Repository\UserNoResultException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Security\UserPasswordEncoderInterface;
use App\User\Query\Model\AccessToken as QueryAccessToken;
use App\User\Query\Model\User;

final class AccessTokenCreateCommandHandler implements CommandHandlerInterface
{
    private AccessTokenRepositoryInterface $accessTokenRepository;
    private EventBusInterface $eventBus;
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(AccessTokenRepositoryInterface $accessTokenRepository, EventBusInterface $eventBus, UserPasswordEncoderInterface $passwordEncoder, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->eventBus = $eventBus;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(AccessTokenCreateCommand $command): array
    {
        try {
            $this->validator->validate($command);

            $user = $this->userRepository->findOneByEmail($command->getEmail());

            // check password match
            if (false === $this->passwordEncoder->isPasswordValid($user, $command->getPassword())) {
                throw new UserNoResultException();
            }

            $accessToken = (new AccessToken())
                ->setUser($user)
            ;

            $this->validator->validate($accessToken);

            $this->accessTokenRepository->persist($accessToken);
            $this->accessTokenRepository->flush();

            $event = new AccessTokenCreatedEvent(
                $accessToken->getId(),
                $accessToken->getUser()->getId()
            );

            $this->validator->validate($event);

            $this->eventBus->dispatch($event);

            return [
                'access_token' => new QueryAccessToken($accessToken->getId(), new User($accessToken->getUser()->getId())),
            ];
        } catch (UserNoResultException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('email', 'access_token.validator.constraint.bad_credentials'),
            ]);
        }
    }
}
