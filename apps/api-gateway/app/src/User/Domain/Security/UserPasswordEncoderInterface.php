<?php

declare(strict_types=1);

namespace App\User\Domain\Security;

interface UserPasswordEncoderInterface
{
    public function encodePassword(string $plainPassword): string;
}
