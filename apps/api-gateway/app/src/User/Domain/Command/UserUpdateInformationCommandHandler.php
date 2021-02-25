<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ValidatorInterface;
use App\User\Domain\Event\UserUpdatedInformationEvent;
use App\User\Domain\Repository\UserRepositoryInterface;

final class UserUpdateInformationCommandHandler implements CommandHandlerInterface
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

    public function __invoke(UserUpdateInformationCommand $command): void
    {
        $user = $this->userRepository->findOne($command->getId());

        $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $user);

        $user->updateGender($command->getGender());
        $user->updateName($command->getName());

        $this->validator->validate($user);

        $this->userRepository->flush();

        $this->eventBus->dispatch(new UserUpdatedInformationEvent(
            $user->getId(),
            $user->getGender(),
            $user->getName()
        ));
    }
}
