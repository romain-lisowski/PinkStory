<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Exception\ValidatorException;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserRegeneratePasswordForgottenSecretCommandHandler
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private UserRepositoryInterface $userRepository;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, UserRepositoryInterface $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    public function handle(UserRegeneratePasswordForgottenSecretCommand $command): void
    {
        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $user = $this->userRepository->findOneByEmail($command->email);

        $user->regeneratePasswordForgottenSecret();
        $user->updateLastUpdatedAt();

        $this->entityManager->flush();
    }
}
