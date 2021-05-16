<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Validator\ConstraintViolation;
use App\Common\Domain\Validator\ValidationFailedException;
use App\Common\Domain\Validator\ValidatorInterface;
use App\User\Domain\Event\UserUpdatedPasswordForgottenEvent;
use App\User\Domain\Repository\UserNoResultException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Security\UserPasswordEncoderInterface;

final class UserUpdatePasswordForgottenCommandHandler implements CommandHandlerInterface
{
    private EventBusInterface $eventBus;
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(EventBusInterface $eventBus, UserPasswordEncoderInterface $passwordEncoder, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->eventBus = $eventBus;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(UserUpdatePasswordForgottenCommand $command): array
    {
        try {
            $this->validator->validate($command);

            $user = $this->userRepository->findOneByActivePasswordForgottenSecret($command->getSecret());

            $user->updatePassword($command->getPassword(), $this->passwordEncoder);

            $this->validator->validate($user);

            $this->userRepository->flush();

            $event = new UserUpdatedPasswordForgottenEvent(
                $user->getId(),
                $user->getPassword()
            );

            $this->validator->validate($event);

            $this->eventBus->dispatch($event);

            return [];
        } catch (UserNoResultException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('secret', 'user.validator.constraint.secret_not_found'),
            ]);
        }
    }
}
