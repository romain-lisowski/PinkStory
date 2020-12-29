<?php

declare(strict_types=1);

namespace App\User\Model;

use App\Model\AbstractEnum;

final class UserStatus extends AbstractEnum
{
    public const ACTIVATED = 'ACTIVATED';
    public const BLOCKED = 'BLOCKED';

    protected static string $translationPrefix = 'user_status.value.';
}
