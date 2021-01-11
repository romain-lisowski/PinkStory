<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

use App\Common\Domain\Model\AbstractEnum;

final class UserRole extends AbstractEnum
{
    public const GOD = 'GOD';
    public const ADMIN = 'ADMIN';
    public const MODERATOR = 'MODERATOR';
    public const EDITOR = 'EDITOR';
    public const USER = 'USER';

    protected static string $translationPrefix = 'user.field.user_role.value.';
}
