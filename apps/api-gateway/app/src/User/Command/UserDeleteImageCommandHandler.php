<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandHandler;
use App\File\ImageManagerInterface;
use App\Model\EditableInterface;
use App\Security\AuthorizationManagerInterface;
use App\User\Message\UserDeleteImageMessage;
use App\User\Repository\Entity\UserRepositoryInterface;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class UserDeleteImageCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private AuthorizationManagerInterface $authorizationManager;
    private ImageManagerInterface $imageManager;
    private UserRepositoryInterface $userRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, AuthorizationManagerInterface $authorizationManager, ImageManagerInterface $imageManager, UserRepositoryInterface $userRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->authorizationManager = $authorizationManager;
        $this->imageManager = $imageManager;
        $this->userRepository = $userRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): void
    {
        $this->validatorManager->validate($this->command);

        $user = $this->userRepository->findOne($this->command->id);

        $this->authorizationManager->isGranted(EditableInterface::UPDATE, $user);

        if (false === $user->hasImage()) {
            return;
        }

        $user->deleteImage();
        $user->updateLastUpdatedAt();

        $this->validatorManager->validate($user);

        $this->imageManager->delete($user);

        $this->entityManager->flush();

        $this->bus->dispatch(new UserDeleteImageMessage($user->getId()));
    }
}
