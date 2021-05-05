<?php

declare(strict_types=1);

namespace App\Story\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use App\Story\Query\Query\StoryImageSearchQuery;
use Doctrine\Common\Collections\Collection;

interface StoryImageRepositoryInterface extends RepositoryInterface
{
    public function search(StoryImageSearchQuery $query): Collection;

    public function countForSearch(StoryImageSearchQuery $query): int;
}
