<?php

declare(strict_types=1);

namespace App\Story\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Model\PositionUpdateException;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ConstraintViolation;
use App\Common\Domain\Validator\ValidationFailedException;
use App\Common\Domain\Validator\ValidatorInterface;
use App\Language\Domain\Repository\LanguageRepositoryInterface;
use App\Story\Domain\Event\StoryUpdatedChildrenPositionEvent;
use App\Story\Domain\Repository\StoryImageRepositoryInterface;
use App\Story\Domain\Repository\StoryRepositoryInterface;
use App\Story\Domain\Repository\StoryThemeRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;

final class StoryUpdateChildrenPositionCommandHandler implements CommandHandlerInterface
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

    public function __invoke(StoryUpdateChildrenPositionCommand $command): array
    {
        try {
            $this->validator->validate($command);

            $story = $this->storyRepository->findOne($command->getId());

            $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $story);

            $story->updateChildrenPositions($command->getChildrenIds());

            $this->validator->validate($story);

            $this->storyRepository->flush();

            $event = new StoryUpdatedChildrenPositionEvent(
                $story->getId(),
                $story->extractChildrenPositionedIds()
            );

            $this->validator->validate($event);

            $this->eventBus->dispatch($event);

            return [];
        } catch (PositionUpdateException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('children_ids', 'story.validator.constraint.children_position_not_found'),
            ]);
        }
    }
}
