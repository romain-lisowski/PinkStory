<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Validator\ConstraintViolation;
use App\Common\Domain\Validator\ValidationFailedException;
use App\Common\Domain\Validator\ValidatorInterface;
use App\User\Domain\Event\UserRegeneratePasswordForgottenSecretEvent;
use App\User\Domain\Repository\UserNoResultException;
use App\User\Domain\Repository\UserRepositoryInterface;

final class UserRegeneratePasswordForgottenSecretCommandHandler implements CommandHandlerInterface
{
    private EventBusInterface $eventBus;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(EventBusInterface $eventBus, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->eventBus = $eventBus;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(UserRegeneratePasswordForgottenSecretCommand $command): array
    {
        try {
            $this->validator->validate($command);

            $user = $this->userRepository->findOneByEmail($command->getEmail());

            $user->regeneratePasswordForgottenSecret();

            $this->validator->validate($user);

            $this->userRepository->flush();

            $event = (new UserRegeneratePasswordForgottenSecretEvent())
                ->setId($user->getId())
                ->setEmail($user->getEmail())
                ->setPasswordForgottenSecret($user->getPasswordForgottenSecret())
            ;

            $this->validator->validate($event);

            $this->eventBus->dispatch($event);

            return [];
        } catch (UserNoResultException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('email', 'user.validator.constraint.email_not_found'),
            ]);
        }
    }
}