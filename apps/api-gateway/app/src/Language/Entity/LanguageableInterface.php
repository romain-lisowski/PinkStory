<?php

declare(strict_types=1);

namespace App\Language\Entity;

interface LanguageableInterface
{
    public function getLanguage(): Language;

    public function setLanguage(Language $language): self;
}
