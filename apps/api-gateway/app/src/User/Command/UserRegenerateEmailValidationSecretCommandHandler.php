<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Exception\ValidatorException;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserRegenerateEmailValidationSecretCommandHandler
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

    public function handle(UserRegenerateEmailValidationSecretCommand $command): void
    {
        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $user = $this->userRepository->findOne($command->id);

        $user->regenerateEmailValidationSecret();
        $user->updateLastUpdatedAt();

        $this->entityManager->flush();
    }
}
