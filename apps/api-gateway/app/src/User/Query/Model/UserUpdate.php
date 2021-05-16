<?php

declare(strict_types=1);

namespace App\User\Query\Model;

use App\Language\Query\Model\Language;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class UserUpdate extends UserMedium
{
    private string $email;
    private Collection $readingLanguages;

    public function __construct()
    {
        $this->readingLanguages = new ArrayCollection();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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
