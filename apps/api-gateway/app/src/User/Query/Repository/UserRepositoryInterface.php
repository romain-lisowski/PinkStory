<?php

declare(strict_types=1);

namespace App\User\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use App\User\Domain\Repository\UserNoResultException;
use App\User\Query\Model\User;
use App\User\Query\Model\UserCurrent;
use App\User\Query\Model\UserFull;
use App\User\Query\Model\UserUpdate;
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
    public function findOneLite(string $id): User;

    /**
     * @throws UserNoResultException
     */
    public function findOneForUpdate(UserGetForUpdateQuery $query): UserUpdate;

    /**
     * @throws UserNoResultException
     */
    public function findOneForCurrent(string $id): UserCurrent;
}
