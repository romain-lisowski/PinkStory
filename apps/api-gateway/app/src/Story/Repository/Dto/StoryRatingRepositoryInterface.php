<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Story\Model\Dto\StoryRating;
use Doctrine\Common\Collections\Collection;

interface StoryRatingRepositoryInterface
{
    public function getOneForStoryAndUser(string $storyId, string $userId): ?StoryRating;

    public function populateStories(Collection $stories): void;
}
