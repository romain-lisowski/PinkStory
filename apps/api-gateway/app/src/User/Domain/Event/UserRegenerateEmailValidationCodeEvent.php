<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\Common\Domain\Event\EventInterface;
use App\User\Infrastructure\Validator\Constraint as AppUserAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class UserRegenerateEmailValidationCodeEvent implements EventInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $id;

    /**
     * @Assert\NotBlank
     * @AppUserAssert\Email
     */
    private string $email;

    /**
     * @Assert\NotBlank
     * @Assert\Regex("/^([0-9]{6})$/")
     */
    private string $emailValidationCode;

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

    public function getEmailValidationCode(): string
    {
        return $this->emailValidationCode;
    }

    public function setEmailValidationCode(string $emailValidationCode): self
    {
        $this->emailValidationCode = $emailValidationCode;

        return $this;
    }
}
