<?php

declare(strict_types=1);

namespace App\Story\Command;

use App\Command\AbstractCommandHandler;
use App\Model\EditableInterface;
use App\Security\AuthorizationManagerInterface;
use App\Story\Message\StoryUpdateChildrenPositionMessage;
use App\Story\Model\Entity\Story;
use App\Story\Repository\Entity\StoryRepositoryInterface;
use App\Validator\ValidatorManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class StoryUpdateChildrenPositionCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private AuthorizationManagerInterface $authorizationManager;
    private StoryRepositoryInterface $storyRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, AuthorizationManagerInterface $authorizationManager, StoryRepositoryInterface $storyRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->authorizationManager = $authorizationManager;
        $this->storyRepository = $storyRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): void
    {
        $this->validatorManager->validate($this->command);

        $story = $this->storyRepository->findOneParent($this->command->id);

        $this->authorizationManager->isGranted(EditableInterface::UPDATE, $story);

        Story::updatePositions($story->getChildren(), new ArrayCollection($this->command->childIds));

        foreach ($story->getChildren() as $child) {
            $child->updateLastUpdatedAt();

            $this->validatorManager->validate($child);
        }

        $story->updateLastUpdatedAt();

        $this->validatorManager->validate($story);

        $this->entityManager->flush();

        $this->bus->dispatch(new StoryUpdateChildrenPositionMessage($story->getId()));
    }
}
