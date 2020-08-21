<?php

declare(strict_types=1);

namespace App\User\Command;

use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class UserRegenerateEmailValidationSecretCommandHandler
{
    private EntityManagerInterface $entityManager;
    private UserRepositoryInterface $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepositoryInterface $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function handle(UserRegenerateEmailValidationSecretCommand $command): void
    {
        $user = $this->userRepository->findOne($command->id);

        $user->regenerateEmailValidationSecret();
        $user->updateLastUpdatedAt();

        $this->entityManager->flush();
    }
}
