<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandHandler;
use App\User\Message\UserRegeneratePasswordForgottenSecretMessage;
use App\User\Repository\UserRepositoryInterface;
use App\Validator\ValidatorException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserRegeneratePasswordForgottenSecretCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private ValidatorInterface $validator;
    private UserRepositoryInterface $userRepository;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, ValidatorInterface $validator, UserRepositoryInterface $userRepository)
    {
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

        $user = $this->userRepository->findOneByEmail($this->command->email);

        $user->regeneratePasswordForgottenSecret();
        $user->updateLastUpdatedAt();

        $this->entityManager->flush();

        $this->bus->dispatch(new UserRegeneratePasswordForgottenSecretMessage($user->getId()));
    }
}
