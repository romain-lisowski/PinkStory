<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandHandler;
use App\Exception\ValidatorException;
use App\File\ImageManagerInterface;
use App\User\Message\UserRemoveImageMessage;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserRemoveImageCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private ValidatorInterface $validator;
    private ImageManagerInterface $imageManager;
    private UserRepositoryInterface $userRepository;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, ValidatorInterface $validator, ImageManagerInterface $imageManager, UserRepositoryInterface $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->validator = $validator;
        $this->imageManager = $imageManager;
        $this->userRepository = $userRepository;
    }

    public function handle(): void
    {
        $errors = $this->validator->validate($this->command);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $user = $this->userRepository->findOne($this->command->id);

        if (false === $user->hasImage()) {
            return;
        }

        $this->imageManager->remove($user);

        $user->removeImage();
        $user->updateLastUpdatedAt();

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $this->entityManager->flush();

        $this->bus->dispatch(new UserRemoveImageMessage($user->getId()));
    }
}
