<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandHandler;
use App\Model\EditableInterface;
use App\Security\AuthorizationManagerInterface;
use App\User\Message\UserUpdatePasswordMessage;
use App\User\Repository\Entity\UserRepositoryInterface;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserUpdatePasswordCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private UserPasswordEncoderInterface $passwordEncoder;
    private AuthorizationManagerInterface $authorizationManager;
    private UserRepositoryInterface $userRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, UserPasswordEncoderInterface $passwordEncoder, AuthorizationManagerInterface $authorizationManager, UserRepositoryInterface $userRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->passwordEncoder = $passwordEncoder;
        $this->authorizationManager = $authorizationManager;
        $this->userRepository = $userRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): void
    {
        $this->validatorManager->validate($this->command);

        $user = $this->userRepository->findOne($this->command->id);

        $this->authorizationManager->isGranted(EditableInterface::UPDATE, $user);

        $user->updatePassword($this->passwordEncoder->encodePassword($user, $this->command->password));
        $user->updateLastUpdatedAt();

        $this->validatorManager->validate($user);

        $this->entityManager->flush();

        $this->bus->dispatch(new UserUpdatePasswordMessage($user->getId()));
    }
}
