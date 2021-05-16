<?php

declare(strict_types=1);

namespace App\Story\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use App\Story\Domain\Repository\StoryNoResultException;
use App\Story\Query\Model\StoryFull;
use App\Story\Query\Model\StoryUpdate;
use App\Story\Query\Query\StoryGetForUpdateQuery;
use App\Story\Query\Query\StoryGetQuery;
use App\Story\Query\Query\StorySearchQuery;
use Doctrine\Common\Collections\Collection;

interface StoryRepositoryInterface extends RepositoryInterface
{
    /**
     * @throws StoryNoResultException
     */
    public function findOne(StoryGetQuery $query): StoryFull;

    public function findOneForUpdate(StoryGetForUpdateQuery $query): StoryUpdate;

    public function findBySearch(StorySearchQuery $query): Collection;

    public function countBySearch(StorySearchQuery $query): int;
}
