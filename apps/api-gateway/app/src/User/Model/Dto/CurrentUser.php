<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\Language\Model\Dto\CurrentLanguage;
use App\User\Model\UserGender;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

final class CurrentUser extends UserMedium implements UserInterface, UserReadingLanguageableInterface
{
    use UserReadingLanguageableTrait;

    private string $secret;

    private string $role;

    public function __construct(string $id = '', bool $imageDefined = false, string $name = '', string $nameSlug = '', string $gender = UserGender::UNDEFINED, string $secret = '', string $role = '', DateTime $createdAt, CurrentLanguage $language)
    {
        parent::__construct($id, $imageDefined, $name, $nameSlug, $gender, $createdAt, $language);

        $this->secret = $secret;
        $this->role = $role;
        $this->readingLanguages = new ArrayCollection();
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
        return $this->getId();
    }

    public function eraseCredentials()
    {
        return null;
    }
}
