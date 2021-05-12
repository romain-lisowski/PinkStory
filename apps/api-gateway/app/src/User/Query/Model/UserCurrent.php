<?php

declare(strict_types=1);

namespace App\User\Query\Model;

use Symfony\Component\Security\Core\User\UserInterface;

final class UserCurrent extends UserFull implements UserInterface
{
    public const ROLE_PREFIX = 'ROLE_';

    private string $role;

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->getId();
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
