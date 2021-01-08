<?php

declare(strict_types=1);

namespace App\Story\Command;

use App\Command\AbstractCommandHandler;
use App\Model\EditableInterface;
use App\Security\AuthorizationManagerInterface;
use App\Story\Message\StoryDeleteMessage;
use App\Story\Repository\Entity\StoryRepositoryInterface;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class StoryDeleteCommandHandler extends AbstractCommandHandler
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

        $story = $this->storyRepository->findOne($this->command->id);

        $this->authorizationManager->isGranted(EditableInterface::DELETE, $story);

        $this->entityManager->remove($story);
        $this->entityManager->flush();

        $this->bus->dispatch(new StoryDeleteMessage($story->getId()));
    }
}
