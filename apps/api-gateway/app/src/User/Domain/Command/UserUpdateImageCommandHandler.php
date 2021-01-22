<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\File\ImageManagerInterface;
use App\Common\Domain\Validator\ValidatorInterface;
use App\User\Domain\Event\UserUpdatedImageEvent;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Security\UserPasswordEncoderInterface;

final class UserUpdateImageCommandHandler implements CommandHandlerInterface
{
    private EventBusInterface $eventBus;
    private ImageManagerInterface $imageManager;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(EventBusInterface $eventBus, ImageManagerInterface $imageManager, UserPasswordEncoderInterface $passwordEncoder, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->eventBus = $eventBus;
        $this->imageManager = $imageManager;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(UserUpdateImageCommand $command): void
    {
        $user = $this->userRepository->findOne($command->getId());

        $user->updateImageDefined(true);

        $this->validator->validate($user);

        $this->imageManager->upload($command->getImage(), $user);

        $this->userRepository->flush();

        $this->eventBus->dispatch(new UserUpdatedImageEvent(
            $user->getId(),
            $user->getImagePath()
        ));
    }
}
