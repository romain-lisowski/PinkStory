<?php

declare(strict_types=1);

namespace App\User\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use App\User\Domain\Repository\UserNoResultException;
use App\User\Query\Model\UserCurrent;
use App\User\Query\Model\UserForUpdate;
use App\User\Query\Model\UserFull;
use App\User\Query\Query\UserGetForUpdateQuery;
use App\User\Query\Query\UserGetQuery;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * @throws UserNoResultException
     */
    public function findOne(UserGetQuery $query): UserFull;

    /**
     * @throws UserNoResultException
     */
    public function findOneForUpdate(UserGetForUpdateQuery $query): UserForUpdate;

    /**
     * @throws UserNoResultException
     */
    public function findOneForCurrent(string $id): UserCurrent;
}
