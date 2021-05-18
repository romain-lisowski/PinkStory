<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use App\Common\Infrastructure\Validator\Constraint as AppAssert;
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
     * @AppAssert\Entity(
     *      entityClass = "App\User\Domain\Model\User",
     *      expr = "repository.findOneByActivePasswordForgottenSecret(value)",
     *      message = "user.validator.constraint.secret_not_found"
     * )
     */
    private string $secret;

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }
}
