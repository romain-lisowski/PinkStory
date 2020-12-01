<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class UserRegeneratePasswordForgottenSecretCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public string $email;
}
