<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ValidatorInterface;
use App\User\Domain\Event\UserRegenerateEmailValidationCodeEvent;
use App\User\Domain\Repository\UserRepositoryInterface;

final class UserRegenerateEmailValidationCodeCommandHandler implements CommandHandlerInterface
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

    public function __invoke(UserRegenerateEmailValidationCodeCommand $command): array
    {
        $this->validator->validate($command);

        $user = $this->userRepository->findOne($command->getId());

        $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $user);

        $user->regenerateEmailValidationCode();

        $this->validator->validate($user);

        $this->userRepository->flush();

        $event = new UserRegenerateEmailValidationCodeEvent(
            $user->getId(),
            $user->getEmail(),
            $user->getEmailValidationCode()
        );

        $this->validator->validate($event);

        $this->eventBus->dispatch($event);

        return [];
    }
}
