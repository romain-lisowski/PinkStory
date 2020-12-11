<?php

declare(strict_types=1);

namespace App\Story\Dto;

use Doctrine\Common\Collections\Collection;

interface IdentifiableInterface
{
    public static function extractIds(Collection $identifiables): array;
}
