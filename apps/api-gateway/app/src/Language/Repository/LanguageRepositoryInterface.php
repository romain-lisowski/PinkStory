<?php

declare(strict_types=1);

namespace App\Language\Repository;

use App\Language\Entity\Language;

interface LanguageRepositoryInterface
{
    public function findOne(string $id): Language;

    public function findOneByLocale(string $locale): Language;
}
