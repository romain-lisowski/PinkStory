<?php

declare(strict_types=1);

namespace App\User\Repository\Entity;

use App\User\Model\Entity\User;

interface UserRepositoryInterface
{
    public function findOne(string $id): User;

    public function findOneByEmail(string $email): User;

    public function findOneByActiveEmailValidationCode(string $code): User;

    public function findOneByActivePasswordForgottenSecret(string $secret): User;
}
