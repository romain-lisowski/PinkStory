<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Validator\ValidatorInterface;
use App\Common\Infrastructure\Messenger\CommandHandlerInterface;
use App\User\Domain\Model\User;

final class UserCreateCommandHandler implements CommandHandlerInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function __invoke(UserCreateCommand $command)
    {
        $user = (new User())->generateId()
            ->setGender($command->getGender())
            ->setName($command->getName())
            ->setEmail($command->getEmail())
            ->setPassword($command->getPassword())
        ;

        $this->validator->validate($user);
    }
}
