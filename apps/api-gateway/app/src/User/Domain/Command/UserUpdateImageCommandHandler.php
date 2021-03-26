<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\File\ImageManagerInterface;
use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ValidatorInterface;
use App\User\Domain\Event\UserUpdatedImageEvent;
use App\User\Domain\Repository\UserRepositoryInterface;

final class UserUpdateImageCommandHandler implements CommandHandlerInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private EventBusInterface $eventBus;
    private ImageManagerInterface $imageManager;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, EventBusInterface $eventBus, ImageManagerInterface $imageManager, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->eventBus = $eventBus;
        $this->imageManager = $imageManager;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(UserUpdateImageCommand $command): void
    {
        $this->validator->validate($command);

        $user = $this->userRepository->findOne($command->getId());

        $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $user);

        $user->updateImageDefined(true);

        $this->validator->validate($user);

        $this->imageManager->upload($command->getImage(), $user);

        $this->userRepository->flush();

        $event = new UserUpdatedImageEvent(
            $user->getId(),
            $user->getImagePath()
        );

        $this->validator->validate($event);

        $this->eventBus->dispatch($event);
    }
}
