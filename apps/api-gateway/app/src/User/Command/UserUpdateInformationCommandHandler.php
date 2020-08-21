<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Exception\ValidatorException;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserUpdateInformationCommandHandler
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

    public function handle(UserUpdateInformationCommand $command): void
    {
        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $user = $this->userRepository->findOne($command->id);

        $user->rename($command->name);
        $user->updateLastUpdatedAt();

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $this->entityManager->flush();
    }
}
