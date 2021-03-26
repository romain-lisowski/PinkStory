<?php

declare(strict_types=1);

namespace App\Language\Domain\Repository;

use App\Common\Domain\Repository\RepositoryInterface;
use App\Language\Domain\Model\Language;

interface LanguageRepositoryInterface extends RepositoryInterface
{
    /**
     * @throws LanguageNoResultException
     */
    public function findOne(string $id): Language;

    /**
     * @throws LanguageNoResultException
     */
    public function findOneByLocale(string $locale): Language;
}
