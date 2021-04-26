<?php

declare(strict_types=1);

namespace App\User\Query\Model;

use App\Language\Query\Model\LanguageMedium;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserCurrent extends UserFull implements UserInterface
{
    public const ROLE_PREFIX = 'ROLE_';

    private string $role;

    public function __construct(string $id, string $gender, string $genderReading, string $name, string $nameSlug, bool $imageDefined, string $role, LanguageMedium $language, \DateTime $createdAt)
    {
        parent::__construct($id, $gender, $genderReading, $name, $nameSlug, $imageDefined, $language, $createdAt);

        $this->role = $role;
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
