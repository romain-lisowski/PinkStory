<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandHandler;
use App\Language\Repository\Entity\LanguageRepositoryInterface;
use App\Model\EditableInterface;
use App\Security\AuthorizationManagerInterface;
use App\User\Message\UserUpdateInformationMessage;
use App\User\Repository\Entity\UserRepositoryInterface;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class UserUpdateInformationCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private AuthorizationManagerInterface $authorizationManager;
    private LanguageRepositoryInterface $languageRepository;
    private UserRepositoryInterface $userRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, AuthorizationManagerInterface $authorizationManager, LanguageRepositoryInterface $languageRepository, UserRepositoryInterface $userRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->authorizationManager = $authorizationManager;
        $this->userRepository = $userRepository;
        $this->languageRepository = $languageRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): void
    {
        $this->validatorManager->validate($this->command);

        $user = $this->userRepository->findOne($this->command->id);

        $this->authorizationManager->isGranted(EditableInterface::UPDATE, $user);

        $language = $this->languageRepository->findOne($this->command->languageId);

        $user->rename($this->command->name);
        $user->updateLanguage($language);
        $user->updateLastUpdatedAt();

        $this->validatorManager->validate($user);

        $this->entityManager->flush();

        $this->bus->dispatch(new UserUpdateInformationMessage($user->getId()));
    }
}
