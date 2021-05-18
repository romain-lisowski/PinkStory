<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use App\Common\Infrastructure\Validator\Constraint as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class AccessTokenCreateCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @AppAssert\Entity(
     *      entityClass = "App\User\Domain\Model\User",
     *      expr = "repository.findOneByEmail(value)",
     *      valueType = "string",
     *      message = "access_token.validator.constraint.bad_credentials"
     * )
     */
    private string $email;

    /**
     * @Assert\NotBlank
     */
    private string $password;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
