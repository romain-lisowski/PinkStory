<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandHandler;
use App\User\Message\UserCreateMessage;
use App\User\Model\Entity\User;
use App\User\Model\UserRole;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserCreateCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private UserPasswordEncoderInterface $passwordEncoder;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, UserPasswordEncoderInterface $passwordEncoder, ValidatorManagerInterface $validatorManager)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->passwordEncoder = $passwordEncoder;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): void
    {
        $this->validatorManager->validate($this->command);

        $user = new User($this->command->name, $this->command->email, UserRole::ROLE_USER, $this->command->language);
        $user->updatePassword($this->passwordEncoder->encodePassword($user, $this->command->password));

        $this->validatorManager->validate($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->bus->dispatch(new UserCreateMessage($user->getId()));
    }
}
