<?php

declare(strict_types=1);

namespace App\Language\Model\Entity;

trait LanguageableTrait
{
    private Language $language;

    public function getLanguage(): Language
    {
        return $this->language;
    }

    abstract public function setLanguage(Language $language): self;

    abstract public function updateLanguage(Language $language): self;
}
