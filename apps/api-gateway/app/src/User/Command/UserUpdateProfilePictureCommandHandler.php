<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Exception\ValidatorException;
use App\User\Exception\ProfilePictureUploadException;
use App\User\Message\UserUpdateProfilePictureMessage;
use App\User\Repository\UserRepositoryInterface;
use App\User\Upload\UserProfilePictureUploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserUpdateProfilePictureCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private ValidatorInterface $validator;
    private UserProfilePictureUploaderInterface $userProfilePictureUploader;
    private UserRepositoryInterface $userRepository;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, ValidatorInterface $validator, UserProfilePictureUploaderInterface $userProfilePictureUploader, UserRepositoryInterface $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->validator = $validator;
        $this->userProfilePictureUploader = $userProfilePictureUploader;
        $this->userRepository = $userRepository;
    }

    public function handle(UserUpdateProfilePictureCommand $command): void
    {
        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $user = $this->userRepository->findOne($command->id);

        $this->userProfilePictureUploader->setUser($user);

        if (true === $user->hasProfilePicture()) {
            $this->userProfilePictureUploader->remove();

            $user->removeProfilePicture();
        }

        if (false === $this->userProfilePictureUploader->upload($command->profilePicture)) {
            throw new ProfilePictureUploadException();
        }

        $user->setProfilePictureDefined(true);
        $user->updateLastUpdatedAt();

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $this->entityManager->flush();

        $this->bus->dispatch(new UserUpdateProfilePictureMessage($user->getId()));
    }
}
