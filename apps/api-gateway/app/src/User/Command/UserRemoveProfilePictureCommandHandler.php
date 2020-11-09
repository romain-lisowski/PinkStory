<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Exception\ValidatorException;
use App\User\Message\UserRemoveProfilePictureMessage;
use App\User\Repository\UserRepositoryInterface;
use App\User\Upload\UserProfilePictureUploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserRemoveProfilePictureCommandHandler
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

    public function handle(UserRemoveProfilePictureCommand $command): void
    {
        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $user = $this->userRepository->findOne($command->id);

        if (false === $user->hasProfilePicture()) {
            return;
        }

        $user->removeProfilePicture();
        $user->updateLastUpdatedAt();

        $this->entityManager->flush();

        $this->userProfilePictureUploader->setUser($user);
        $this->userProfilePictureUploader->remove();

        $this->bus->dispatch(new UserRemoveProfilePictureMessage($user->getId()));
    }
}
