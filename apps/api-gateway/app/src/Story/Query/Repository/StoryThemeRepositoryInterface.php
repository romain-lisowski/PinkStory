<?php

declare(strict_types=1);

namespace App\Story\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use App\Story\Query\Query\StoryThemeSearchQuery;

interface StoryThemeRepositoryInterface extends RepositoryInterface
{
    public function search(StoryThemeSearchQuery $query): \Traversable;
}
