<?php

declare(strict_types=1);

namespace App\Language\Repository\Dto;

use App\Language\Model\Dto\CurrentLanguage;
use App\Language\Query\LanguageSearchQuery;
use Doctrine\Common\Collections\Collection;

interface LanguageRepositoryInterface
{
    public function findCurrentByLocale(string $locale): CurrentLanguage;

    public function search(LanguageSearchQuery $query): Collection;

    public function findIds(): array;
}
