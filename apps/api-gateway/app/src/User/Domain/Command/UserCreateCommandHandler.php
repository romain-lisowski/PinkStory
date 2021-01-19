<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\File\ImageManagerInterface;
use App\Common\Domain\Validator\ValidatorInterface;
use App\User\Domain\Event\UserCreatedEvent;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Security\UserPasswordEncoderInterface;

final class UserCreateCommandHandler implements CommandHandlerInterface
{
    private EventBusInterface $eventBus;
    private ImageManagerInterface $imageManager;
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(EventBusInterface $eventBus, ImageManagerInterface $imageManager, UserPasswordEncoderInterface $passwordEncoder, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->eventBus = $eventBus;
        $this->imageManager = $imageManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(UserCreateCommand $command): void
    {
        $user = (new User())
            ->setGender($command->getGender())
            ->setName($command->getName())
            ->setEmail($command->getEmail())
            ->setPassword($command->getPassword(), $this->passwordEncoder)
            ->setImageDefined(null !== $command->getImage() ? true : false)
            ->setRole($command->getRole())
            ->setStatus($command->getStatus())
        ;

        $this->validator->validate($user);

        if (null !== $command->getImage()) {
            $this->imageManager->upload($command->getImage(), $user);
        }

        $this->userRepository->persist($user);
        $this->userRepository->flush();

        $this->eventBus->dispatch(new UserCreatedEvent($user->getId(), $command));
    }
}
