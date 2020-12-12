<?php

declare(strict_types=1);

namespace App\User\Security;

use App\User\Model\Dto\CurrentUser;

interface UserSecurityManagerInterface
{
    public function getCurrentUser(): ?CurrentUser;
}
