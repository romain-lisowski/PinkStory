<?php

declare(strict_types=1);

namespace App\Story\Command;

use App\Command\AbstractCommandHandler;
use App\Model\EditableInterface;
use App\Security\AuthorizationManagerInterface;
use App\Story\Message\StoryRatingDeleteMessage;
use App\Story\Repository\Entity\StoryRatingRepositoryInterface;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Messenger\MessageBusInterface;

final class StoryRatingDeleteCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private AuthorizationManagerInterface $authorizationManager;
    private StoryRatingRepositoryInterface $storyRatingRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, AuthorizationManagerInterface $authorizationManager, StoryRatingRepositoryInterface $storyRatingRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->authorizationManager = $authorizationManager;
        $this->storyRatingRepository = $storyRatingRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): void
    {
        $this->validatorManager->validate($this->command);

        $storyRating = $this->storyRatingRepository->findOneByStoryAndUser($this->command->storyId, $this->command->userId);

        if (null === $storyRating) {
            throw new NoResultException();
        }

        $this->authorizationManager->isGranted(EditableInterface::DELETE, $storyRating);

        $this->entityManager->remove($storyRating);
        $this->entityManager->flush();

        $this->bus->dispatch(new StoryRatingDeleteMessage($storyRating->getId()));
    }
}
