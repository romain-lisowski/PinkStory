<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandHandler;
use App\Model\EditableInterface;
use App\Security\AuthorizationManagerInterface;
use App\User\Message\UserUpdateInformationMessage;
use App\User\Repository\Entity\UserRepositoryInterface;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class UserUpdateInformationCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private AuthorizationManagerInterface $authorizationManager;
    private UserRepositoryInterface $userRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, AuthorizationManagerInterface $authorizationManager, UserRepositoryInterface $userRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->authorizationManager = $authorizationManager;
        $this->userRepository = $userRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): void
    {
        $this->validatorManager->validate($this->command);

        $user = $this->userRepository->findOne($this->command->id);

        $this->authorizationManager->isGranted(EditableInterface::UPDATE, $user);

        $user->rename($this->command->name);
        $user->updateLanguage($this->command->language);
        $user->updateLastUpdatedAt();

        $this->validatorManager->validate($user);

        $this->entityManager->flush();

        $this->bus->dispatch(new UserUpdateInformationMessage($user->getId()));
    }
}
