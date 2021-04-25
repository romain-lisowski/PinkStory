<?php

declare(strict_types=1);

namespace App\User\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use App\User\Query\Query\AccessTokenSearchQuery;

interface AccessTokenRepositoryInterface extends RepositoryInterface
{
    public function search(AccessTokenSearchQuery $query): \Traversable;
}
