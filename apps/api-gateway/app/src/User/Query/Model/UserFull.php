<?php

declare(strict_types=1);

namespace App\User\Query\Model;

use App\Language\Query\Model\LanguageFull;

final class UserFull extends User
{
    private string $gender;
    private string $genderReading;
    private string $name;
    private string $nameSlug;
    private bool $imageDefined;
    private LanguageFull $language;
    private \DateTime $createdAt;

    public function __construct(string $id, string $gender, string $genderReading, string $name, string $nameSlug, bool $imageDefined, LanguageFull $language, \DateTime $createdAt)
    {
        parent::__construct($id);

        $this->gender = $gender;
        $this->genderReading = $genderReading;
        $this->name = $name;
        $this->nameSlug = $nameSlug;
        $this->imageDefined = $imageDefined;
        $this->language = $language;
        $this->createdAt = $createdAt;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getGenderReading(): string
    {
        return $this->genderReading;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNameSlug(): string
    {
        return $this->nameSlug;
    }

    public function getImageDefined(): bool
    {
        return $this->imageDefined;
    }

    public function getLanguage(): LanguageFull
    {
        return $this->language;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
