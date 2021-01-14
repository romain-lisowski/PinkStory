<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Validator\ValidatorInterface;
use App\Common\Infrastructure\Messenger\CommandHandlerInterface;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Security\UserPasswordEncoderInterface;

final class UserCreateCommandHandler implements CommandHandlerInterface
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(UserCreateCommand $command): void
    {
        $user = (new User())
            ->setGender($command->getGender())
            ->setName($command->getName())
            ->setEmail($command->getEmail())
            ->setPassword($command->getPassword(), $this->passwordEncoder)
            ->setRole($command->getRole())
            ->setStatus($command->getStatus())
        ;

        $this->validator->validate($user);

        $this->userRepository->persist($user);
        $this->userRepository->flush();
    }
}
