<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandHandler;
use App\User\Message\UserUpdateInformationMessage;
use App\User\Repository\UserRepositoryInterface;
use App\User\Voter\UserableVoter;
use App\Validator\ValidatorException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserUpdateInformationCommandHandler extends AbstractCommandHandler
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private ValidatorInterface $validator;
    private UserRepositoryInterface $userRepository;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, EntityManagerInterface $entityManager, MessageBusInterface $bus, ValidatorInterface $validator, UserRepositoryInterface $userRepository)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    public function handle(): void
    {
        $errors = $this->validator->validate($this->command);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $user = $this->userRepository->findOne($this->command->id);

        if (false === $this->authorizationChecker->isGranted(UserableVoter::UPDATE, $user)) {
            throw new AccessDeniedException();
        }

        $user->rename($this->command->name);
        $user->updateLastUpdatedAt();

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $this->entityManager->flush();

        $this->bus->dispatch(new UserUpdateInformationMessage($user->getId()));
    }
}
