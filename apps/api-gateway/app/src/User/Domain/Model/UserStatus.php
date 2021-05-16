<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

use App\Common\Domain\Model\AbstractEnum;

final class UserStatus extends AbstractEnum
{
    public const ACTIVATED = 'ACTIVATED';
    public const BLOCKED = 'BLOCKED';

    protected static string $translationPrefix = 'user.field.user_status.value.';
}
