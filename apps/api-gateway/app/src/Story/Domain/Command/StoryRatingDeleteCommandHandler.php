<?php

declare(strict_types=1);

namespace App\Story\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ValidatorInterface;
use App\Story\Domain\Event\StoryRatingDeletedEvent;
use App\Story\Domain\Repository\StoryRatingRepositoryInterface;
use App\Story\Domain\Repository\StoryRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;

final class StoryRatingDeleteCommandHandler implements CommandHandlerInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private EventBusInterface $eventBus;
    private StoryRepositoryInterface $storyRepository;
    private StoryRatingRepositoryInterface $storyRatingRepository;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, EventBusInterface $eventBus, StoryRepositoryInterface $storyRepository, StoryRatingRepositoryInterface $storyRatingRepository, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->eventBus = $eventBus;
        $this->storyRepository = $storyRepository;
        $this->storyRatingRepository = $storyRatingRepository;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(StoryRatingDeleteCommand $command): array
    {
        $this->validator->validate($command);

        $story = $this->storyRepository->findOne($command->getStoryId());

        $user = $this->userRepository->findOne($command->getUserId());

        $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $user);

        $storyRating = $this->storyRatingRepository->findOneByStoryAndUser($story->getId(), $user->getId());

        $this->authorizationChecker->isGranted(EditableInterface::DELETE, $storyRating);

        $this->storyRatingRepository->remove($storyRating);
        $this->storyRatingRepository->flush();

        $event = (new StoryRatingDeletedEvent())
            ->setStoryId($story->getId())
            ->setUserId($user->getId())
        ;

        $this->validator->validate($event);

        $this->eventBus->dispatch($event);

        return [];
    }
}
