<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Story\Model\Dto\StoryRatingForUpdate;
use Doctrine\Common\Collections\Collection;

interface StoryRatingRepositoryInterface
{
    public function getOneForUpdate(string $storyId, string $userId): ?StoryRatingForUpdate;

    public function populateStories(Collection $stories): void;
}
