<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandHandler;
use App\Model\EditableInterface;
use App\Security\AuthorizationManagerInterface;
use App\User\Repository\UserRepositoryInterface;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class UserValidateEmailCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private AuthorizationManagerInterface $authorizationManager;
    private UserRepositoryInterface $userRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(EntityManagerInterface $entityManager, AuthorizationManagerInterface $authorizationManager, UserRepositoryInterface $userRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->entityManager = $entityManager;
        $this->authorizationManager = $authorizationManager;
        $this->userRepository = $userRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): void
    {
        $this->validatorManager->validate($this->command);

        $user = $this->userRepository->findOneByActiveEmailValidationCode($this->command->code);

        if ($user->getId() !== $this->command->id) {
            throw new AccessDeniedException();
        }

        $this->authorizationManager->isGranted(EditableInterface::UPDATE, $user);

        $user->validateEmail();
        $user->updateLastUpdatedAt();

        $this->validatorManager->validate($user);

        $this->entityManager->flush();
    }
}
