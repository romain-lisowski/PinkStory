<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\Language\Model\Dto\CurrentLanguage;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

final class CurrentUser extends UserMedium implements UserInterface
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $email;

    private string $secret;

    private string $role;

    public function __construct(string $id = '', bool $imageDefined = false, string $name = '', string $nameSlug = '', string $email = '', string $secret = '', string $role = '', DateTime $createdAt, CurrentLanguage $language)
    {
        parent::__construct($id, $imageDefined, $name, $nameSlug, $createdAt, $language);

        $this->email = $email;
        $this->secret = $secret;
        $this->role = $role;
    }

    public function getEmail(): string
    {
        return $this->email;
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
