<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class UserValidateEmailCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $id;

    /**
     * @Assert\NotBlank
     * @Assert\Regex("/^([0-9]{6})$/")
     */
    private string $emailValidationCode;

    public function __construct(string $id, string $emailValidationCode)
    {
        $this->id = $id;
        $this->emailValidationCode = $emailValidationCode;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmailValidationCode(): string
    {
        return $this->emailValidationCode;
    }
}
