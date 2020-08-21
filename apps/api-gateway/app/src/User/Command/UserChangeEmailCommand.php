<?php

declare(strict_types=1);

namespace App\User\Command;

use App\User\Validator\Constraints as AppUserAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class UserChangeEmailCommand
{
    /**
     * @Assert\NotBlank
     */
    public string $id;

    /**
     * @Assert\NotBlank
     * @AppUserAssert\Email
     */
    public string $email;
}
