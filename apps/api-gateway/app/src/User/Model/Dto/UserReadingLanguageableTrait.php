<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\Language\Model\Dto\Language;
use Doctrine\Common\Collections\Collection;

trait UserReadingLanguageableTrait
{
    private Collection $readingLanguages;

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
