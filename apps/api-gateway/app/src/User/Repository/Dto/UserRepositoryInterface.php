<?php

declare(strict_types=1);

namespace App\User\Repository\Dto;

use App\User\Model\Dto\CurrentUser;
use App\User\Model\Dto\UserForUpdate;
use App\User\Query\UserGetForUpdateQuery;

interface UserRepositoryInterface
{
    public function getCurrent(string $id): CurrentUser;

    public function getOneForUpdate(UserGetForUpdateQuery $query): UserForUpdate;
}
