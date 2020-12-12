<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandHandler;
use App\User\Message\UserRegeneratePasswordForgottenSecretMessage;
use App\User\Repository\Entity\UserRepositoryInterface;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class UserRegeneratePasswordForgottenSecretCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private UserRepositoryInterface $userRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, UserRepositoryInterface $userRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->userRepository = $userRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): void
    {
        $this->validatorManager->validate($this->command);

        $user = $this->userRepository->findOneByEmail($this->command->email);

        $user->regeneratePasswordForgottenSecret();
        $user->updateLastUpdatedAt();

        $this->validatorManager->validate($user);

        $this->entityManager->flush();

        $this->bus->dispatch(new UserRegeneratePasswordForgottenSecretMessage($user->getId()));
    }
}
