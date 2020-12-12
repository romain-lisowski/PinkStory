<?php

declare(strict_types=1);

namespace App\User\Query;

use App\Model\EditableInterface;
use App\Query\AbstractQueryHandler;
use App\Security\AuthorizationManagerInterface;
use App\User\Model\Dto\UserFull;
use App\User\Repository\Dto\UserRepositoryInterface;
use App\Validator\ValidatorManagerInterface;

final class UserReadForUpdateQueryHandler extends AbstractQueryHandler
{
    private AuthorizationManagerInterface $authorizationManager;
    private UserRepositoryInterface $userRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(AuthorizationManagerInterface $authorizationManager, UserRepositoryInterface $userRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->authorizationManager = $authorizationManager;
        $this->userRepository = $userRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): UserFull
    {
        $this->validatorManager->validate($this->query);

        $user = $this->userRepository->findOne($this->query->id);

        $this->authorizationManager->isGranted(EditableInterface::UPDATE, $user);

        return $user;
    }
}
