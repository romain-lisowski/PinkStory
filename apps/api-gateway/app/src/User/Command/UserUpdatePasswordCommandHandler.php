<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Exception\ValidatorException;
use App\User\Message\UserUpdatePasswordMessage;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserUpdatePasswordCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private UserPasswordEncoderInterface $passwordEncoder;
    private ValidatorInterface $validator;
    private UserRepositoryInterface $userRepository;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator, UserRepositoryInterface $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    public function handle(UserUpdatePasswordCommand $command): void
    {
        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $user = $this->userRepository->findOne($command->id);

        $user->updatePassword($this->passwordEncoder->encodePassword($user, $command->password));
        $user->updateLastUpdatedAt();

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $this->entityManager->flush();

        $this->bus->dispatch(new UserUpdatePasswordMessage($user->getId()));
    }
}
