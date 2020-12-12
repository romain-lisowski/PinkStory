<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\Language\Model\Dto\CurrentLanguage;
use Symfony\Component\Security\Core\User\UserInterface;

final class CurrentUser extends User implements UserInterface
{
    private string $secret;

    private string $role;

    private CurrentLanguage $language;

    public function __construct(string $id = '', bool $imageDefined = false, string $secret = '', string $role = '', CurrentLanguage $language)
    {
        parent::__construct($id, $imageDefined);

        $this->secret = $secret;
        $this->role = $role;
        $this->language = $language;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getLanguage(): CurrentLanguage
    {
        return $this->language;
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

    public function getUsername()
    {
        return null;
    }

    public function eraseCredentials()
    {
        return null;
    }
}
