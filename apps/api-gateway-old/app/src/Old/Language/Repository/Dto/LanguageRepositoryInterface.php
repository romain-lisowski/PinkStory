<?php

declare(strict_types=1);

namespace App\Language\Repository\Dto;

use App\Language\Model\Dto\CurrentLanguage;
use App\Language\Query\LanguageSearchQuery;
use App\User\Model\Dto\User;
use Doctrine\Common\Collections\Collection;

interface LanguageRepositoryInterface
{
    public function getCurrentByLocale(string $locale): CurrentLanguage;

    public function getBySearch(LanguageSearchQuery $query): Collection;

    public function populateUserReadingLanguages(User $user): void;
}
