<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\Language\Model\Dto\CurrentLanguage;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

final class CurrentUser extends UserFull implements UserInterface
{
    private string $secret;

    private string $role;

    public function __construct(string $id = '', bool $imageDefined = false, string $name = '', string $nameSlug = '', string $email = '', string $secret = '', string $role = '', DateTime $createdAt, CurrentLanguage $language)
    {
        parent::__construct($id, $imageDefined, $name, $nameSlug, $email, $createdAt, $language);

        $this->secret = $secret;
        $this->role = $role;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function getPassword()
    {
        return null;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
        return null;
    }
}
