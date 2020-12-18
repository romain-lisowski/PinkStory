<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Story\Model\Dto\StoryFull;
use App\Story\Query\StoryGetQuery;
use App\Story\Query\StorySearchQuery;
use Doctrine\Common\Collections\Collection;

interface StoryRepositoryInterface
{
    public function getOne(StoryGetQuery $query): StoryFull;

    public function countBySearch(StorySearchQuery $query): int;

    public function getBySearch(StorySearchQuery $query): Collection;
}
