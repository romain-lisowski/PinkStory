<?php

declare(strict_types=1);

namespace App\Story\Repository\Entity;

use App\Story\Model\Entity\StoryRating;

interface StoryRatingRepositoryInterface
{
    public function findOneByStoryAndUser(string $storyId, string $userId): ?StoryRating;
}
