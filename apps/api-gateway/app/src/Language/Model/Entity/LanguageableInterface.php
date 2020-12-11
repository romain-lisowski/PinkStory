<?php

declare(strict_types=1);

namespace App\Language\Model\Entity;

interface LanguageableInterface
{
    public function getLanguage(): Language;

    public function setLanguage(Language $language): self;

    public function updateLanguage(Language $language): self;
}
