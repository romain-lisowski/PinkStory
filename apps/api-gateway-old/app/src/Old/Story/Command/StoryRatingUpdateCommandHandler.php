<?php

declare(strict_types=1);

namespace App\Story\Command;

use App\Command\AbstractCommandHandler;
use App\Model\EditableInterface;
use App\Security\AuthorizationManagerInterface;
use App\Story\Message\StoryRatingUpdateMessage;
use App\Story\Model\Entity\StoryRating;
use App\Story\Repository\Entity\StoryRatingRepositoryInterface;
use App\Story\Repository\Entity\StoryRepositoryInterface;
use App\User\Repository\Entity\UserRepositoryInterface;
use App\Validator\ValidatorManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class StoryRatingUpdateCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private AuthorizationManagerInterface $authorizationManager;
    private StoryRepositoryInterface $storyRepository;
    private StoryRatingRepositoryInterface $storyRatingRepository;
    private UserRepositoryInterface $userRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, AuthorizationManagerInterface $authorizationManager, StoryRepositoryInterface $storyRepository, StoryRatingRepositoryInterface $storyRatingRepository, UserRepositoryInterface $userRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->authorizationManager = $authorizationManager;
        $this->storyRepository = $storyRepository;
        $this->storyRatingRepository = $storyRatingRepository;
        $this->userRepository = $userRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): void
    {
        $this->validatorManager->validate($this->command);

        $user = $this->userRepository->findOne($this->command->userId);
        $story = $this->storyRepository->findOne($this->command->storyId);
        $storyRating = $this->storyRatingRepository->findOneByStoryAndUser($this->command->storyId, $this->command->userId);

        if (null === $storyRating) {
            $storyRating = new StoryRating($this->command->rate, $story, $user);
            $this->entityManager->persist($storyRating);
        }

        $this->authorizationManager->isGranted(EditableInterface::UPDATE, $user);
        $this->authorizationManager->isGranted(EditableInterface::UPDATE, $storyRating);

        $storyRating->updateRate($this->command->rate);
        $storyRating->updateLastUpdatedAt();

        $this->validatorManager->validate($storyRating);

        $this->entityManager->flush();

        $this->bus->dispatch(new StoryRatingUpdateMessage($storyRating->getId()));
    }
}
