<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\Language\Model\Dto\Language;
use Doctrine\Common\Collections\Collection;

interface UserReadingLanguageableInterface
{
    public function getReadingLanguages(): Collection;

    public function addReadingLanguage(Language $readingLanguage): self;
}
