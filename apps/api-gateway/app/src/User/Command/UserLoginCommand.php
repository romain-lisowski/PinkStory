<?php

declare(strict_types=1);

namespace App\User\Command;

use Symfony\Component\Validator\Constraints as Assert;

final class UserLoginCommand
{
    /**
     * @Assert\NotBlank
     */
    public string $email;

    /**
     * @Assert\NotBlank
     */
    public string $password;
}
