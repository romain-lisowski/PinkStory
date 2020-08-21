<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Exception\ValidatorException;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserUpdatePasswordForgottenCommandHandler
{
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordEncoder;
    private ValidatorInterface $validator;
    private UserRepositoryInterface $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator, UserRepositoryInterface $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    public function handle(UserUpdatePasswordForgottenCommand $command): void
    {
        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $user = $this->userRepository->findOneByActivePasswordForgottenSecret($command->secret);

        $user->updatePassword($this->passwordEncoder->encodePassword($user, $command->password));
        $user->updateLastUpdatedAt();

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $this->entityManager->flush();
    }
}
