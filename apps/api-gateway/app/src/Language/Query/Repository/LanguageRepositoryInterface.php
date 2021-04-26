<?php

declare(strict_types=1);

namespace App\Language\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use App\Language\Query\Model\LanguageCurrent;
use App\Language\Query\Query\LanguageSearchQuery;

interface LanguageRepositoryInterface extends RepositoryInterface
{
    public function search(LanguageSearchQuery $query): \Traversable;

    public function findOneByLocaleForCurrent(string $locale): ?LanguageCurrent;
}
