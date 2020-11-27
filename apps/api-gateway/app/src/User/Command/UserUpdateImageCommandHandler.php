<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Exception\ImageUploadException;
use App\Exception\ValidatorException;
use App\File\ImageManagerInterface;
use App\User\Message\UserUpdateImageMessage;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserUpdateImageCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private ValidatorInterface $validator;
    private ImageManagerInterface $imageManagerInterface;
    private UserRepositoryInterface $userRepository;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, ValidatorInterface $validator, ImageManagerInterface $imageManagerInterface, UserRepositoryInterface $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->validator = $validator;
        $this->imageManagerInterface = $imageManagerInterface;
        $this->userRepository = $userRepository;
    }

    public function handle(UserUpdateImageCommand $command): void
    {
        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $user = $this->userRepository->findOne($command->id);

        if (false === $this->imageManagerInterface->upload($command->image, $user)) {
            throw new ImageUploadException();
        }

        $user->setImageDefined(true);
        $user->updateLastUpdatedAt();

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $this->imageManagerInterface->remove($user);

            throw new ValidatorException($errors);
        }

        $this->entityManager->flush();

        $this->bus->dispatch(new UserUpdateImageMessage($user->getId()));
    }
}
