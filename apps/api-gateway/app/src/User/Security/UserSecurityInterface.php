<?php

declare(strict_types=1);

namespace App\User\Security;

use App\User\Entity\User;

interface UserSecurityInterface
{
    public function getUser(): ?User;
}
