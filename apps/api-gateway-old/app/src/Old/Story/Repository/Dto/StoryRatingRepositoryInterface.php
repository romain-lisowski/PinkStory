<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Story\Model\Dto\StoryRatingForUpdate;
use App\Story\Query\StoryRatingGetForUpdateQuery;
use Doctrine\Common\Collections\Collection;

interface StoryRatingRepositoryInterface
{
    public function getOneForUpdate(StoryRatingGetForUpdateQuery $query): ?StoryRatingForUpdate;

    public function populateStories(Collection $stories): void;
}
