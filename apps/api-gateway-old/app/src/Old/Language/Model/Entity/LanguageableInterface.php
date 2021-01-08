<?php

declare(strict_types=1);

namespace App\Language\Model\Entity;

use App\Language\Model\LanguageableInterface as ModelLanguageableInterface;
use App\Language\Model\LanguageInterface;

interface LanguageableInterface extends ModelLanguageableInterface
{
    public function setLanguage(LanguageInterface $language): self;

    public function updateLanguage(LanguageInterface $language): self;
}
