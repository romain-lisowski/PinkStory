<?php

declare(strict_types=1);

namespace App\Language\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use App\Language\Query\Model\LanguageCurrent;
use App\Language\Query\Query\LanguageSearchQuery;
use App\User\Query\Model\UserMedium;
use Doctrine\Common\Collections\Collection;

interface LanguageRepositoryInterface extends RepositoryInterface
{
    public function findBySearch(LanguageSearchQuery $query): Collection;

    public function findOneByLocaleForCurrent(string $locale): ?LanguageCurrent;

    public function findOneByAccessTokenForCurrent(string $accessTokenId): ?LanguageCurrent;

    public function populateUserReadingLanguages(UserMedium $user, string $languageClass = Language::class): void;
}
