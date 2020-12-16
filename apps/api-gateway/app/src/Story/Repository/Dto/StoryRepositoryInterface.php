<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Story\Model\Dto\StoryFull;
use App\Story\Query\StoryGetQuery;

interface StoryRepositoryInterface
{
    public function getOne(StoryGetQuery $query): StoryFull;
}
