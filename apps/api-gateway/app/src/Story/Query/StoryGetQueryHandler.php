<?php

declare(strict_types=1);

namespace App\Story\Query;

use App\Query\AbstractQueryHandler;
use App\Story\Repository\Dto\StoryRatingRepositoryInterface;
use App\Story\Repository\Dto\StoryRepositoryInterface;
use App\User\Security\UserSecurityManagerInterface;
use App\Validator\ValidatorManagerInterface;

final class StoryGetQueryHandler extends AbstractQueryHandler
{
    private StoryRepositoryInterface $storyRepository;
    private StoryRatingRepositoryInterface $storyRatingRepository;
    private UserSecurityManagerInterface $userSecurityManager;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(StoryRepositoryInterface $storyRepository, StoryRatingRepositoryInterface $storyRatingRepository, UserSecurityManagerInterface $userSecurityManager, ValidatorManagerInterface $validatorManager)
    {
        $this->storyRepository = $storyRepository;
        $this->storyRatingRepository = $storyRatingRepository;
        $this->userSecurityManager = $userSecurityManager;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): array
    {
        $this->validatorManager->validate($this->query);

        $story = $this->storyRepository->getOne($this->query);

        $currentStoryRating = null;

        if (null !== $this->userSecurityManager->getCurrentUser()) {
            $currentStoryRating = $this->storyRatingRepository->getOneForUpdate($this->query->id, $this->userSecurityManager->getCurrentUser()->getId());
        }

        return [
            'story' => $story,
            'current_story_rating' => $currentStoryRating,
        ];
    }
}
