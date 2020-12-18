<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandHandler;
use App\Language\Repository\Entity\LanguageRepositoryInterface;
use App\User\Message\UserCreateMessage;
use App\User\Model\Entity\User;
use App\User\Model\UserRole;
use App\User\Model\UserStatus;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserCreateCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private LanguageRepositoryInterface $languageRepository;
    private UserPasswordEncoderInterface $passwordEncoder;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, LanguageRepositoryInterface $languageRepository, UserPasswordEncoderInterface $passwordEncoder, ValidatorManagerInterface $validatorManager)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->languageRepository = $languageRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): void
    {
        $this->validatorManager->validate($this->command);

        $language = $this->languageRepository->findOne($this->command->languageId);

        $user = new User($this->command->name, $this->command->email, UserRole::ROLE_USER, UserStatus::ACTIVATED, $language);
        $user->updatePassword($this->passwordEncoder->encodePassword($user, $this->command->password));

        $this->validatorManager->validate($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->bus->dispatch(new UserCreateMessage($user->getId()));
    }
}
