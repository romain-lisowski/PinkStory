<?php

declare(strict_types=1);

namespace App\Story\Domain\Repository;

use App\Common\Domain\Repository\RepositoryInterface;
use App\Story\Domain\Model\StoryRating;

interface StoryRatingRepositoryInterface extends RepositoryInterface
{
    public function findOneByStoryAndUser(string $storyId, string $userId): ?StoryRating;
}
