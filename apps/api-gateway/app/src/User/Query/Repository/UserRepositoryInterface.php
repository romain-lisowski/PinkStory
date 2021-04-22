<?php

declare(strict_types=1);

namespace App\User\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use App\User\Query\Model\UserForUpdate;
use App\User\Query\Model\UserFull;
use App\User\Query\Query\UserGetForUpdateQuery;
use App\User\Query\Query\UserGetQuery;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findOne(UserGetQuery $query): UserFull;

    public function findOneForUpdate(UserGetForUpdateQuery $query): UserForUpdate;
}
