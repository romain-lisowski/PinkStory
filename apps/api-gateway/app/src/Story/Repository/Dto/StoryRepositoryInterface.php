<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Story\Model\Dto\StoryForUpdate;
use App\Story\Model\Dto\StoryFull;
use App\Story\Query\StoryGetForUpdateQuery;
use App\Story\Query\StoryGetQuery;
use App\Story\Query\StorySearchQuery;
use Doctrine\Common\Collections\Collection;

interface StoryRepositoryInterface
{
    public function getOne(StoryGetQuery $query): StoryFull;

    public function getOneForUpdate(StoryGetForUpdateQuery $query): StoryForUpdate;

    public function countBySearch(StorySearchQuery $query): int;

    public function getBySearch(StorySearchQuery $query): Collection;
}
