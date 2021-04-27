<?php

declare(strict_types=1);

namespace App\User\Query\Model;

use App\Language\Query\Model\Language;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class UserMedium extends User
{
    private string $gender;
    private string $name;
    private bool $imageDefined;
    private Language $language;
    private Collection $readingLanguages;

    public function __construct(string $id, string $gender, string $name, bool $imageDefined, Language $language)
    {
        parent::__construct($id);

        $this->gender = $gender;
        $this->name = $name;
        $this->imageDefined = $imageDefined;
        $this->language = $language;
        $this->readingLanguages = new ArrayCollection();
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImageDefined(): bool
    {
        return $this->imageDefined;
    }

    public function getLanguage(): Language
    {
        return $this->language;
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
