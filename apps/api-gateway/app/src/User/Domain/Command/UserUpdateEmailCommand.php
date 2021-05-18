<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use App\Common\Infrastructure\Validator\Constraint as AppAssert;
use App\User\Infrastructure\Validator\Constraint as AppUserAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class UserUpdateEmailCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     * @AppAssert\Entity(
     *      entityClass = "App\User\Domain\Model\User",
     *      message = "user.validator.constraint.user_not_found"
     * )
     */
    private string $id;

    /**
     * @Assert\NotBlank
     * @AppUserAssert\Email
     */
    private string $email;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

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
