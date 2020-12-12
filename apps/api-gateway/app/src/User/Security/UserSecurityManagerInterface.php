<?php

declare(strict_types=1);

namespace App\User\Security;

use App\User\Model\Entity\User;

interface UserSecurityManagerInterface
{
    public function getUser(): ?User;
}
