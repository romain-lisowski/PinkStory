<?php

declare(strict_types=1);

namespace App\Story\Command;

use App\Command\AbstractCommandHandler;
use App\Language\Repository\Entity\LanguageRepositoryInterface;
use App\Model\EditableInterface;
use App\Security\AuthorizationManagerInterface;
use App\Story\Message\StoryUpdateMessage;
use App\Story\Repository\Entity\StoryImageRepositoryInterface;
use App\Story\Repository\Entity\StoryRepositoryInterface;
use App\Story\Repository\Entity\StoryThemeRepositoryInterface;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class StoryUpdateCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private AuthorizationManagerInterface $authorizationManager;
    private LanguageRepositoryInterface $languageRepository;
    private StoryRepositoryInterface $storyRepository;
    private StoryImageRepositoryInterface $storyImageRepository;
    private StoryThemeRepositoryInterface $storyThemeRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, AuthorizationManagerInterface $authorizationManager, LanguageRepositoryInterface $languageRepository, StoryRepositoryInterface $storyRepository, StoryImageRepositoryInterface $storyImageRepository, StoryThemeRepositoryInterface $storyThemeRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->authorizationManager = $authorizationManager;
        $this->languageRepository = $languageRepository;
        $this->storyRepository = $storyRepository;
        $this->storyImageRepository = $storyImageRepository;
        $this->storyThemeRepository = $storyThemeRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): void
    {
        $this->validatorManager->validate($this->command);

        $story = $this->storyRepository->findOne($this->command->id);

        $this->authorizationManager->isGranted(EditableInterface::UPDATE, $story);

        $story->updateTitle($this->command->title);
        $story->updateContent($this->command->content);
        $story->updateExtract($this->command->extract);

        if (null !== $this->command->storyImageId) {
            $storyImage = $this->storyImageRepository->findOne($this->command->storyImageId);
            $story->updateStoryImage($storyImage);
        }

        if (null !== $this->command->languageId) {
            $language = $this->languageRepository->findOne($this->command->languageId);
            $story->updateLanguage($language);
        }

        $story->updateStoryThemes($this->command->storyThemeIds, $this->storyThemeRepository);

        $story->updateLastUpdatedAt();

        $this->validatorManager->validate($story);

        $this->entityManager->flush();

        $this->bus->dispatch(new StoryUpdateMessage($story->getId()));
    }
}
