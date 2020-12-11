<?php

declare(strict_types=1);

namespace App\Language\Repository\Dto;

use App\Language\Query\LanguageSearchQuery;
use Doctrine\Common\Collections\Collection;

interface LanguageRepositoryInterface
{
    public function search(LanguageSearchQuery $query): Collection;
}
