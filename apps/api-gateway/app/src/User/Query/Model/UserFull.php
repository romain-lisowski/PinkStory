<?php

declare(strict_types=1);

namespace App\User\Query\Model;

use App\Language\Query\Model\LanguageMedium;

class UserFull extends UserMedium
{
    private string $genderReading;
    private string $nameSlug;
    private \DateTime $createdAt;

    public function __construct(string $id, string $gender, string $genderReading, string $name, string $nameSlug, bool $imageDefined, LanguageMedium $language, \DateTime $createdAt)
    {
        parent::__construct($id, $gender, $name, $imageDefined, $language);

        $this->genderReading = $genderReading;
        $this->nameSlug = $nameSlug;
        $this->createdAt = $createdAt;
    }

    public function getGenderReading(): string
    {
        return $this->genderReading;
    }

    public function getNameSlug(): string
    {
        return $this->nameSlug;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
