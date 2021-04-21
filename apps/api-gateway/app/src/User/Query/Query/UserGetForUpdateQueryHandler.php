<?php

declare(strict_types=1);

namespace App\User\Query\Query;

use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ValidatorInterface;
use App\Common\Query\Query\QueryHandlerInterface;
use App\User\Query\Model\UserForUpdate;
use App\User\Query\Repository\UserRepositoryInterface;

final class UserGetForUpdateQueryHandler implements QueryHandlerInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(UserGetForUpdateQuery $query): UserForUpdate
    {
        $this->validator->validate($query);

        $user = $this->userRepository->findOneForUpdate($query);

        $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $user);

        return $user;
    }
}
