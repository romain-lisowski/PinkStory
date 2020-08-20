<?php

declare(strict_types=1);

namespace App\User\Command;

use Symfony\Component\Validator\Constraints as Assert;

final class UserValidateEmailCommand
{
    /**
     * @Assert\NotBlank
     */
    public string $id;

    /**
     * @Assert\NotBlank
     */
    public string $secret;
}
