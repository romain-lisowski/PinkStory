<?php

declare(strict_types=1);

namespace App\User\Domain\Security;

use App\User\Domain\Model\User;

interface UserPasswordEncoderInterface
{
    public function encodePassword(string $plainPassword): string;

    public function isPasswordValid(User $user, string $plainPassword): bool;
}
