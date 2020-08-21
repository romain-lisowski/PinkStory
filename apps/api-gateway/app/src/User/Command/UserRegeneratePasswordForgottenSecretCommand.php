<?php

declare(strict_types=1);

namespace App\User\Command;

use Symfony\Component\Validator\Constraints as Assert;

final class UserRegeneratePasswordForgottenSecretCommand
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public string $email;
}
