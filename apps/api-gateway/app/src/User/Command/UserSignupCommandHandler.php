<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Exception\ValidatorException;
use App\User\Entity\User;
use App\User\Validator\Constraints\PasswordStrenght;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserSignupCommandHandler
{
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordEncoder;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
    }

    public function handle(UserSignupCommand $command): void
    {
        $errors = $this->validator->validate($command->password, new PasswordStrenght());

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $user = new User();
        $user->rename($command->name)
            ->changeEmail($command->email)
            ->changePassword($this->passwordEncoder->encodePassword($user, $command->password))
        ;

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
