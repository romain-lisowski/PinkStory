<?php

declare(strict_types=1);

namespace App\Language\Model;

interface LanguageableInterface
{
    public function getLanguage(): LanguageInterface;
}
