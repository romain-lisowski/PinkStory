<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class User implements UserInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $id;

    /**
     * @Assert\NotBlank
     * @Assert\Choice(callback={"App\User\Domain\Model\UserRole", "getChoices"})
     */
    private string $role;

    public function __construct(string $id, string $role)
    {
        $this->id = $id;
        $this->role = $role;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function getPassword(): ?string
    {
        return null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
