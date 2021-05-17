<?php

declare(strict_types=1);

namespace App\Story\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ValidatorInterface;
use App\Story\Domain\Event\StoryDeletedEvent;
use App\Story\Domain\Repository\StoryRepositoryInterface;

final class StoryDeleteCommandHandler implements CommandHandlerInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private EventBusInterface $eventBus;
    private StoryRepositoryInterface $storyRepository;
    private ValidatorInterface $validator;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, EventBusInterface $eventBus, StoryRepositoryInterface $storyRepository, ValidatorInterface $validator)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->eventBus = $eventBus;
        $this->storyRepository = $storyRepository;
        $this->validator = $validator;
    }

    public function __invoke(StoryDeleteCommand $command): array
    {
        $this->validator->validate($command);

        $story = $this->storyRepository->findOne($command->getId());

        $this->authorizationChecker->isGranted(EditableInterface::DELETE, $story);

        $this->storyRepository->remove($story);
        $this->storyRepository->flush();

        $event = (new StoryDeletedEvent())
            ->setId($story->getId())
        ;

        $this->validator->validate($event);

        $this->eventBus->dispatch($event);

        return [];
    }
}
