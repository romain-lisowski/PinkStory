<?php

declare(strict_types=1);

namespace App\User\Domain\Security;

use App\User\Query\Model\UserCurrent;

interface SecurityInterface
{
    public function getUser(): ?UserCurrent;
}
