<?php

declare(strict_types=1);

namespace App\User\Query\Query;

use App\Common\Domain\Validator\ValidatorInterface;
use App\Common\Query\Query\QueryHandlerInterface;
use App\User\Query\Model\UserFull;
use App\User\Query\Repository\UserRepositoryInterface;

final class UserGetQueryHandler implements QueryHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(UserGetQuery $query): UserFull
    {
        $this->validator->validate($query);

        return $this->userRepository->findOne($query);
    }
}
