<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Validator\ValidatorInterface;
use App\Common\Infrastructure\Messenger\CommandHandlerInterface;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;

final class UserCreateCommandHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(UserCreateCommand $command): void
    {
        $user = (new User())
            ->updateGender($command->getGender())
            ->rename($command->getName())
            ->updateEmail($command->getEmail())
            ->updatePassword($command->getPassword())
            ->updateRole($command->getRole())
            ->updateStatus($command->getStatus())
        ;

        $this->validator->validate($user);

        $this->userRepository->persist($user);
        $this->userRepository->flush();
    }
}
