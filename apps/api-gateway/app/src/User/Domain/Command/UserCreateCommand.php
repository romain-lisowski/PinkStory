<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use App\User\Infrastructure\Validator\Constraint as AppUserAssert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

final class UserCreateCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Choice(callback={"App\User\Domain\Model\UserGender", "getChoices"})
     */
    private string $gender;

    /**
     * @Assert\NotBlank
     */
    private string $name;

    /**
     * @Assert\NotBlank
     * @AppUserAssert\Email
     */
    private string $email;

    /**
     * @Assert\NotBlank
     * @AppUserAssert\PasswordStrenght
     */
    private string $password;

    /**
     * @Assert\File(
     *      mimeTypes = {"image/jpeg", "image/png"},
     * )
     */
    private ?File $image;

    /**
     * @Assert\NotBlank
     * @Assert\Choice(callback={"App\User\Domain\Model\UserRole", "getChoices"})
     */
    private string $role;

    /**
     * @Assert\NotBlank
     * @Assert\Choice(callback={"App\User\Domain\Model\UserStatus", "getChoices"})
     */
    private string $status;

    public function __construct(string $gender, string $name, string $email, string $password, ?File $image = null, string $role, string $status)
    {
        $this->gender = $gender;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->image = $image;
        $this->role = $role;
        $this->status = $status;
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

    public function getImage(): ?File
    {
        return $this->image;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
