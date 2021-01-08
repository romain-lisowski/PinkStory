<?php

declare(strict_types=1);

namespace App\User\Model;

use App\Model\AbstractEnum;

final class UserRole extends AbstractEnum
{
    public const ROLE_GOD = 'ROLE_GOD';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_MODERATOR = 'ROLE_MODERATOR';
    public const ROLE_EDITOR = 'ROLE_EDITOR';
    public const ROLE_USER = 'ROLE_USER';

    protected static string $translationPrefix = 'user_role.value.';
}
