<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandHandler;
use App\User\Message\UserUpdateEmailMessage;
use App\User\Repository\UserRepositoryInterface;
use App\User\Voter\UserableVoter;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class UserUpdateEmailCommandHandler extends AbstractCommandHandler
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private UserRepositoryInterface $userRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, EntityManagerInterface $entityManager, MessageBusInterface $bus, UserRepositoryInterface $userRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->userRepository = $userRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): void
    {
        $this->validatorManager->validate($this->command);

        $user = $this->userRepository->findOne($this->command->id);

        if (false === $this->authorizationChecker->isGranted(UserableVoter::UPDATE, $user)) {
            throw new AccessDeniedException();
        }

        $user->updateEmail($this->command->email);
        $user->updateLastUpdatedAt();

        $this->validatorManager->validate($user);

        $this->entityManager->flush();

        $this->bus->dispatch(new UserUpdateEmailMessage($user->getId()));
    }
}
