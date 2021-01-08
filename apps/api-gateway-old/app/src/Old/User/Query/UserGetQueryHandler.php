<?php

declare(strict_types=1);

namespace App\User\Query;

use App\Query\AbstractQueryHandler;
use App\User\Model\Dto\UserFull;
use App\User\Repository\Dto\UserRepositoryInterface;
use App\Validator\ValidatorManagerInterface;

final class UserGetQueryHandler extends AbstractQueryHandler
{
    private UserRepositoryInterface $userRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(UserRepositoryInterface $userRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->userRepository = $userRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): UserFull
    {
        $this->validatorManager->validate($this->query);

        return $this->userRepository->getOne($this->query);
    }
}
