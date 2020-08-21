<?php

declare(strict_types=1);

namespace App\User\Command;

use App\User\Validator\Constraints as AppUserAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class UserChangePasswordForgottenCommand
{
    /**
     * @Assert\NotBlank
     */
    public string $secret;

    /**
     * @Assert\NotBlank
     * @AppUserAssert\PasswordStrenght
     */
    public string $password;
}
