<?php

declare(strict_types=1);

namespace App\Language\Repository;

use App\Language\Entity\Language;
use App\Language\Query\LanguageSearchQuery;
use Doctrine\Common\Collections\Collection;

interface LanguageRepositoryInterface
{
    public function findOne(string $id): Language;

    public function findOneByLocale(string $locale): Language;

    public function search(LanguageSearchQuery $query): Collection;
}
