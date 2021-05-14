<?php

declare(strict_types=1);

namespace App\Story\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use App\Story\Query\Query\StorySearchQuery;
use Doctrine\Common\Collections\Collection;

interface StoryRepositoryInterface extends RepositoryInterface
{
    public function findBySearch(StorySearchQuery $query): Collection;

    public function countBySearch(StorySearchQuery $query): int;
}
