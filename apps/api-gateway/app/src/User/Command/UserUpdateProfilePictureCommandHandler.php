<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Exception\ValidatorException;
use App\User\Exception\UserProfilePictureUploadException;
use App\User\File\UserProfilePictureFileManagerInterface;
use App\User\Message\UserUpdateProfilePictureMessage;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserUpdateProfilePictureCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private ValidatorInterface $validator;
    private UserProfilePictureFileManagerInterface $userProfilePictureFileManager;
    private UserRepositoryInterface $userRepository;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, ValidatorInterface $validator, UserProfilePictureFileManagerInterface $userProfilePictureFileManager, UserRepositoryInterface $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->validator = $validator;
        $this->userProfilePictureFileManager = $userProfilePictureFileManager;
        $this->userRepository = $userRepository;
    }

    public function handle(UserUpdateProfilePictureCommand $command): void
    {
        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $user = $this->userRepository->findOne($command->id);

        $this->userProfilePictureFileManager->setUser($user);

        if (false === $this->userProfilePictureFileManager->upload($command->profilePicture)) {
            throw new UserProfilePictureUploadException();
        }

        $user->setProfilePictureDefined(true);
        $user->updateLastUpdatedAt();

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $this->userProfilePictureFileManager->remove();

            throw new ValidatorException($errors);
        }

        $this->entityManager->flush();

        $this->bus->dispatch(new UserUpdateProfilePictureMessage($user->getId()));
    }
}
