<?php

declare(strict_types=1);

namespace App\Story\Command;

use App\Command\AbstractCommandHandler;
use App\Language\Repository\Entity\LanguageRepositoryInterface;
use App\Model\EditableInterface;
use App\Security\AuthorizationManagerInterface;
use App\Story\Message\StoryCreateMessage;
use App\Story\Model\Entity\Story;
use App\Story\Repository\Entity\StoryImageRepositoryInterface;
use App\Story\Repository\Entity\StoryRepositoryInterface;
use App\Story\Repository\Entity\StoryThemeRepositoryInterface;
use App\User\Repository\Entity\UserRepositoryInterface;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class StoryCreateCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private AuthorizationManagerInterface $authorizationManager;
    private LanguageRepositoryInterface $languageRepository;
    private StoryRepositoryInterface $storyRepository;
    private StoryImageRepositoryInterface $storyImageRepository;
    private StoryThemeRepositoryInterface $storyThemeRepository;
    private UserRepositoryInterface $userRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, AuthorizationManagerInterface $authorizationManager, LanguageRepositoryInterface $languageRepository, StoryRepositoryInterface $storyRepository, StoryImageRepositoryInterface $storyImageRepository, StoryThemeRepositoryInterface $storyThemeRepository, UserRepositoryInterface $userRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->authorizationManager = $authorizationManager;
        $this->languageRepository = $languageRepository;
        $this->storyRepository = $storyRepository;
        $this->storyImageRepository = $storyImageRepository;
        $this->storyThemeRepository = $storyThemeRepository;
        $this->userRepository = $userRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): void
    {
        $this->validatorManager->validate($this->command);

        $language = $this->languageRepository->findOne($this->command->languageId);
        $user = $this->userRepository->findOne($this->command->userId);

        $storyImage = null;

        if (null !== $this->command->storyImageId) {
            $storyImage = $this->storyImageRepository->findOne($this->command->storyImageId);
        }

        $storyParent = null;

        if (null !== $this->command->parentId) {
            $storyParent = $this->storyRepository->findOne($this->command->parentId);
            $this->authorizationManager->isGranted(EditableInterface::UPDATE, $storyParent);
        }

        $story = new Story($this->command->title, $this->command->content, $user, $language, $storyParent, null, $storyImage);

        foreach ($this->command->storyThemeIds as $storyThemeId) {
            $storyTheme = $this->storyThemeRepository->findOne($storyThemeId);
            $story->addStoryTheme($storyTheme);
        }

        $this->validatorManager->validate($story);

        $this->entityManager->persist($story);
        $this->entityManager->flush();

        $this->bus->dispatch(new StoryCreateMessage($story->getId()));
    }
}
