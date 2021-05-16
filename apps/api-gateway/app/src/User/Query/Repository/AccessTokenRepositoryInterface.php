<?php

declare(strict_types=1);

namespace App\User\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use App\User\Query\Query\AccessTokenSearchQuery;
use Doctrine\Common\Collections\Collection;

interface AccessTokenRepositoryInterface extends RepositoryInterface
{
    public function findBySearch(AccessTokenSearchQuery $query): Collection;
}
