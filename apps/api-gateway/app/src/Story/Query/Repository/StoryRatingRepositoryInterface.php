<?php

declare(strict_types=1);

namespace App\Story\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use App\Story\Query\Model\StoryRatingUpdate;
use Doctrine\Common\Collections\Collection;

interface StoryRatingRepositoryInterface extends RepositoryInterface
{
    public function findOneForUpdate(string $storyId, string $userId): ?StoryRatingUpdate;

    public function populateStories(Collection $stories): void;
}
