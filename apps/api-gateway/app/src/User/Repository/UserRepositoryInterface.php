<?php

declare(strict_types=1);

namespace App\User\Repository;

use App\User\Entity\User;

interface UserRepositoryInterface
{
    public function findOne(string $id): User;

    public function findOneByEmail(string $email): User;

    public function findOneByActiveEmailValidationSecret(string $secret): User;
}
