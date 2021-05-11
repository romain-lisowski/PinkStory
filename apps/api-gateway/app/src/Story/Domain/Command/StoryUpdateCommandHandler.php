<?php

declare(strict_types=1);

namespace App\Story\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ConstraintViolation;
use App\Common\Domain\Validator\ValidationFailedException;
use App\Common\Domain\Validator\ValidatorInterface;
use App\Language\Domain\Repository\LanguageNoResultException;
use App\Language\Domain\Repository\LanguageRepositoryInterface;
use App\Story\Domain\Event\StoryUpdatedEvent;
use App\Story\Domain\Model\StoryTheme;
use App\Story\Domain\Model\StoryThemeDepthException;
use App\Story\Domain\Repository\StoryImageNoResultException;
use App\Story\Domain\Repository\StoryImageRepositoryInterface;
use App\Story\Domain\Repository\StoryRepositoryInterface;
use App\Story\Domain\Repository\StoryThemeNoResultException;
use App\Story\Domain\Repository\StoryThemeRepositoryInterface;

final class StoryUpdateCommandHandler implements CommandHandlerInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private EventBusInterface $eventBus;
    private LanguageRepositoryInterface $languageRepository;
    private StoryRepositoryInterface $storyRepository;
    private StoryImageRepositoryInterface $storyImageRepository;
    private StoryThemeRepositoryInterface $storyThemeRepository;
    private ValidatorInterface $validator;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, EventBusInterface $eventBus, LanguageRepositoryInterface $languageRepository, StoryRepositoryInterface $storyRepository, StoryImageRepositoryInterface $storyImageRepository, StoryThemeRepositoryInterface $storyThemeRepository, ValidatorInterface $validator)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->eventBus = $eventBus;
        $this->languageRepository = $languageRepository;
        $this->storyRepository = $storyRepository;
        $this->storyImageRepository = $storyImageRepository;
        $this->storyThemeRepository = $storyThemeRepository;
        $this->validator = $validator;
    }

    public function __invoke(StoryUpdateCommand $command): array
    {
        try {
            $this->validator->validate($command);

            $story = $this->storyRepository->findOne($command->getId());

            $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $story);

            $language = $this->languageRepository->findOne($command->getLanguageId());

            $story
                ->updateTitle($command->getTitle())
                ->updateContent($command->getContent())
                ->updateExtract($command->getExtract())
                ->updateLanguage($language)
            ;

            if (null !== $command->getStoryImageId()) {
                $storyImage = $this->storyImageRepository->findOne($command->getStoryImageId());
                $story->updateStoryImage($storyImage);
            } else {
                $story->updateStoryImage(null);
            }

            $story->updateStoryThemes($command->getStoryThemeIds(), $this->storyThemeRepository);

            $this->validator->validate($story);

            $this->storyRepository->flush();

            $event = new StoryUpdatedEvent(
                $story->getId(),
                $story->getTitle(),
                $story->getTitleSlug(),
                $story->getContent(),
                $story->getExtract(),
                $story->getLanguage()->getId(),
                (null !== $story->getStoryImage() ? $story->getStoryImage()->getId() : null),
                StoryTheme::extractIds($story->getStoryThemes()->toArray())
            );

            $this->validator->validate($event);

            $this->eventBus->dispatch($event);

            return [];
        } catch (LanguageNoResultException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('language_id', 'language.validator.constraint.language_not_found'),
            ]);
        } catch (StoryThemeDepthException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('story_theme_ids', $e->getMessage()),
            ]);
        } catch (StoryImageNoResultException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('story_image_id', 'story_image.validator.constraint.story_image_not_found'),
            ]);
        } catch (StoryThemeNoResultException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('story_theme_ids', 'story_theme.validator.constraint.story_theme_not_found'),
            ]);
        }
    }
}
