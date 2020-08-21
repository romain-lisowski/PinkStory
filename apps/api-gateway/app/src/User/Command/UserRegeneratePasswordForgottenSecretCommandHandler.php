<?php

declare(strict_types=1);

namespace App\User\Command;

use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class UserRegeneratePasswordForgottenSecretCommandHandler
{
    private EntityManagerInterface $entityManager;
    private UserRepositoryInterface $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepositoryInterface $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function handle(UserRegeneratePasswordForgottenSecretCommand $command): void
    {
        $user = $this->userRepository->findOneByEmail($command->email);

        $user->regeneratePasswordForgottenSecret();
        $user->updateLastUpdatedAt();

        $this->entityManager->flush();
    }
}
