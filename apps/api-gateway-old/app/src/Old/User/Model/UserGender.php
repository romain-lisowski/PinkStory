<?php

declare(strict_types=1);

namespace App\User\Model;

use App\Model\AbstractEnum;

final class UserGender extends AbstractEnum
{
    public const UNDEFINED = 'UNDEFINED';
    public const MALE = 'MALE';
    public const FEMALE = 'FEMALE';
    public const OTHER = 'OTHER';

    protected static string $translationPrefix = 'user_gender.value.';
}
