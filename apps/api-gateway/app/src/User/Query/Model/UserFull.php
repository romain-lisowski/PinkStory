<?php

declare(strict_types=1);

namespace App\User\Query\Model;

use App\Language\Query\Model\Language;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class UserFull extends UserMedium
{
    private string $genderReading;
    private string $nameSlug;
    private Collection $readingLanguages;

    public function __construct()
    {
        $this->readingLanguages = new ArrayCollection();
    }

    public function getGenderReading(): string
    {
        return $this->genderReading;
    }

    public function setGenderReading(string $genderReading): self
    {
        $this->genderReading = $genderReading;

        return $this;
    }

    public function getNameSlug(): string
    {
        return $this->nameSlug;
    }

    public function setNameSlug(string $nameSlug): self
    {
        $this->nameSlug = $nameSlug;

        return $this;
    }

    public function getReadingLanguages(): Collection
    {
        return $this->readingLanguages;
    }

    public function addReadingLanguage(Language $readingLanguage): self
    {
        $this->readingLanguages[] = $readingLanguage;

        return $this;
    }
}
