<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

use App\Common\Domain\Model\AbstractEnum;

final class UserGender extends AbstractEnum
{
    public const UNDEFINED = 'UNDEFINED';
    public const MALE = 'MALE';
    public const FEMALE = 'FEMALE';
    public const OTHER = 'OTHER';

    protected static string $translationPrefix = 'user.field.user_gender.value.';
}
