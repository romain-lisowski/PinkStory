<?php

declare(strict_types=1);

namespace App\User\Command;

use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class UserValidateEmailCommandHandler
{
    private EntityManagerInterface $entityManager;
    private UserRepositoryInterface $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepositoryInterface $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function handle(UserValidateEmailCommand $command): void
    {
        $user = $this->userRepository->findOneByNotUsedEmailValidationSecret($command->secret);

        if ($user->getId() !== $command->id) {
            throw new AccessDeniedException();
        }

        $user->validateEmail();
        $user->updateLastUpdatedAt();

        $this->entityManager->flush();
    }
}
