<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\Common\Domain\Event\EventInterface;
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
     */
    private string $emailValidationCode;

    public function __construct(string $id, string $email, string $emailValidationCode)
    {
        $this->id = $id;
        $this->email = $email;
        $this->emailValidationCode = $emailValidationCode;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getEmailValidationCode(): string
    {
        return $this->emailValidationCode;
    }
}
