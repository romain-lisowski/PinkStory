<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Security;

use App\User\Query\Model\UserCurrent;

interface SecurityInterface
{
    public function getUser(): ?UserCurrent;
}
