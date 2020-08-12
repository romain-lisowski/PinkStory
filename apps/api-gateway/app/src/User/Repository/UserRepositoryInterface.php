<?php

declare(strict_types=1);

namespace App\User\Repository;

use App\User\Entity\User;
use Symfony\Component\Uid\Uuid;

interface UserRepositoryInterface
{
    public function findOne(Uuid $uuid): User;

    public function findOneByEmail(string $email): User;
}
