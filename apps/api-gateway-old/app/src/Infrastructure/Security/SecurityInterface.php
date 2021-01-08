<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

interface SecurityInterface
{
    public function getUser(): ?User;
}
