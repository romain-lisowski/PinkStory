<?php

declare(strict_types=1);

namespace App\Story\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Model\ChildDepthException;
use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ConstraintViolation;
use App\Common\Domain\Validator\ValidationFailedException;
use App\Common\Domain\Validator\ValidatorInterface;
use App\Language\Domain\Repository\LanguageNoResultException;
use App\Language\Domain\Repository\LanguageRepositoryInterface;
use App\Story\Domain\Event\StoryCreatedEvent;
use App\Story\Domain\Model\Story;
use App\Story\Domain\Model\StoryTheme;
use App\Story\Domain\Model\StoryThemeDepthException;
use App\Story\Domain\Repository\StoryImageNoResultException;
use App\Story\Domain\Repository\StoryImageRepositoryInterface;
use App\Story\Domain\Repository\StoryNoResultException;
use App\Story\Domain\Repository\StoryRepositoryInterface;
use App\Story\Domain\Repository\StoryThemeNoResultException;
use App\Story\Domain\Repository\StoryThemeRepositoryInterface;
use App\Story\Query\Model\Story as QueryStory;
use App\User\Domain\Repository\UserNoResultException;
use App\User\Domain\Repository\UserRepositoryInterface;

final class StoryCreateCommandHandler implements CommandHandlerInterface
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
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(StoryCreateCommand $command): array
    {
        try {
            $this->validator->validate($command);

            $user = $this->userRepository->findOne($command->getUserId());

            $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $user);

            $language = $this->languageRepository->findOne($command->getLanguageId());

            $story = (new Story())
                ->setTitle($command->getTitle())
                ->setContent($command->getContent())
                ->setExtract($command->getExtract())
                ->setUser($user)
                ->setLanguage($language)
            ;

            if (null !== $command->getParentId()) {
                $storyParent = $this->storyRepository->findOne($command->getParentId());

                $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $storyParent);

                $story->setParent($storyParent);
            }

            if (null !== $command->getStoryImageId()) {
                $storyImage = $this->storyImageRepository->findOne($command->getStoryImageId());
                $story->setStoryImage($storyImage);
            }

            $story->addStoryThemes($command->getStoryThemeIds(), $this->storyThemeRepository);

            $this->validator->validate($story);

            $this->storyRepository->persist($story);
            $this->storyRepository->flush();

            $event = new StoryCreatedEvent(
                $story->getId(),
                $story->getTitle(),
                $story->getTitleSlug(),
                $story->getContent(),
                $story->getExtract(),
                $story->getUser()->getId(),
                $story->getLanguage()->getId(),
                (null !== $story->getParent() ? $story->getParent()->getId() : null),
                (null !== $story->getStoryImage() ? $story->getStoryImage()->getId() : null),
                StoryTheme::extractIds($story->getStoryThemes()->toArray())
            );

            $this->validator->validate($event);

            $this->eventBus->dispatch($event);

            return [
                'story' => new QueryStory($story->getId()),
            ];
        } catch (UserNoResultException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('user_id', 'user.validator.constraint.user_not_found'),
            ]);
        } catch (LanguageNoResultException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('language_id', 'language.validator.constraint.language_not_found'),
            ]);
        } catch (StoryNoResultException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('parent_id', 'story.validator.constraint.story_not_found'),
            ]);
        } catch (StoryThemeDepthException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('story_theme_ids', $e->getMessage()),
            ]);
        } catch (ChildDepthException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('parent_id', $e->getMessage()),
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
