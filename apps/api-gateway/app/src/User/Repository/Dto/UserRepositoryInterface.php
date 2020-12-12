<?php

declare(strict_types=1);

namespace App\User\Repository\Dto;

use App\User\Model\Dto\CurrentUser;
use App\User\Model\Dto\UserFull;

interface UserRepositoryInterface
{
    public function findCurrent(string $id): CurrentUser;

    public function findOne(string $id): UserFull;
}
