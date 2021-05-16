<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use App\User\Infrastructure\Validator\Constraint as AppUserAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class UserUpdatePasswordForgottenCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @AppUserAssert\PasswordStrenght
     */
    private string $password;

    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $secret;

    public function __construct(string $password, string $secret)
    {
        $this->password = $password;
        $this->secret = $secret;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }
}
