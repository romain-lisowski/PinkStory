<?php

declare(strict_types=1);

namespace App\Language\Model\Entity;

use App\Language\Model\LanguageableTrait as ModelLanguageableTrait;
use App\Language\Model\LanguageInterface;

trait LanguageableTrait
{
    use ModelLanguageableTrait;

    abstract public function setLanguage(LanguageInterface $language): self;

    abstract public function updateLanguage(LanguageInterface $language): self;
}
