<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ConstraintViolation;
use App\Common\Domain\Validator\ValidationFailedException;
use App\Common\Domain\Validator\ValidatorInterface;
use App\User\Domain\Event\UserValidatedEmailEvent;
use App\User\Domain\Repository\UserRepositoryInterface;

final class UserValidateEmailCommandHandler implements CommandHandlerInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private EventBusInterface $eventBus;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, EventBusInterface $eventBus, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->eventBus = $eventBus;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(UserValidateEmailCommand $command): array
    {
        $this->validator->validate($command);

        $user = $this->userRepository->findOne($command->getId());

        $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $user);

        if ($user->getEmailValidationCode() !== $command->getCode()) {
            throw new ValidationFailedException([
                new ConstraintViolation('code', 'user.validator.constraint.invalid_email_validation_code'),
            ]);
        }

        $user->validateEmail();

        $this->validator->validate($user);

        $this->userRepository->flush();

        $event = new UserValidatedEmailEvent(
            $user->getId(),
            $user->getEmail()
        );

        $this->validator->validate($event);

        $this->eventBus->dispatch($event);

        return [];
    }
}
