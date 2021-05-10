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
use App\Story\Domain\Event\StoryDeletedEvent;
use App\Story\Domain\Model\StoryThemeDepthException;
use App\Story\Domain\Repository\StoryImageNoResultException;
use App\Story\Domain\Repository\StoryImageRepositoryInterface;
use App\Story\Domain\Repository\StoryRepositoryInterface;
use App\Story\Domain\Repository\StoryThemeNoResultException;
use App\Story\Domain\Repository\StoryThemeRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;

final class StoryDeleteCommandHandler implements CommandHandlerInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private EventBusInterface $eventBus;
    private LanguageRepositoryInterface $languageRepository;
    private StoryRepositoryInterface $storyRepository;
    private StoryImageRepositoryInterface $storyImageRepository;
    private StoryThemeRepositoryInterface $storyThemeRepository;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, EventBusInterface $eventBus, LanguageRepositoryInterface $languageRepository, StoryRepositoryInterface $storyRepository, StoryImageRepositoryInterface $storyImageRepository, StoryThemeRepositoryInterface $storyThemeRepository, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->eventBus = $eventBus;
        $this->languageRepository = $languageRepository;
        $this->storyRepository = $storyRepository;
        $this->storyImageRepository = $storyImageRepository;
        $this->storyThemeRepository = $storyThemeRepository;
        $this->validator = $validator;
    }

    public function __invoke(StoryDeleteCommand $command): array
    {
        try {
            $this->validator->validate($command);

            $story = $this->storyRepository->findOne($command->getId());

            $this->authorizationChecker->isGranted(EditableInterface::DELETE, $story);

            $this->storyRepository->remove($story);
            $this->storyRepository->flush();

            $event = new StoryDeletedEvent(
                $story->getId()
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
