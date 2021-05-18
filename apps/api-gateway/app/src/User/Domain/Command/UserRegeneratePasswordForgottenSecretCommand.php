<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use App\Common\Infrastructure\Validator\Constraint as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class UserRegeneratePasswordForgottenSecretCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @AppAssert\Entity(
     *      entityClass = "App\User\Domain\Model\User",
     *      expr = "repository.findOneByEmail(value)",
     *      valueType = "string",
     *      message = "user.validator.constraint.user_not_found"
     * )
     */
    private string $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
