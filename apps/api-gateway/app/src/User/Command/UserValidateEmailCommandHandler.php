<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandHandler;
use App\User\Repository\UserRepositoryInterface;
use App\User\Voter\UserableVoter;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class UserValidateEmailCommandHandler extends AbstractCommandHandler
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private EntityManagerInterface $entityManager;
    private UserRepositoryInterface $userRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, EntityManagerInterface $entityManager, UserRepositoryInterface $userRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
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

        if (false === $this->authorizationChecker->isGranted(UserableVoter::UPDATE, $user)) {
            throw new AccessDeniedException();
        }

        $user->validateEmail();
        $user->updateLastUpdatedAt();

        $this->validatorManager->validate($user);

        $this->entityManager->flush();
    }
}
