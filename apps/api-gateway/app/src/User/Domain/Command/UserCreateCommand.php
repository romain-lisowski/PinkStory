<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Infrastructure\Messenger\MessageInterface;
use App\User\Infrastructure\Validator\Constraint as AppUserAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class UserCreateCommand implements MessageInterface
{
    /**
     * @Assert\NotBlank
     */
    private string $gender;

    /**
     * @Assert\NotBlank
     */
    private string $name;

    /**
     * @Assert\NotBlank
     */
    private string $email;

    /**
     * @Assert\NotBlank
     * @AppUserAssert\PasswordStrenght
     */
    private string $password;

    public function __construct(string $gender, string $name, string $email, string $password)
    {
        $this->gender = $gender;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
