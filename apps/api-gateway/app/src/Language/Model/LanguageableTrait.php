<?php

declare(strict_types=1);

namespace App\Language\Model;

trait LanguageableTrait
{
    private LanguageInterface $language;

    public function getLanguage(): LanguageInterface
    {
        return $this->language;
    }
}
