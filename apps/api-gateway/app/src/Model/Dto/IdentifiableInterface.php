<?php

declare(strict_types=1);

namespace App\Model\Dto;

use App\Model\IdentifiableInterface as ModelIdentifiableInterface;
use Doctrine\Common\Collections\Collection;

interface IdentifiableInterface extends ModelIdentifiableInterface
{
    public function getId(): string;

    public static function extractIds(Collection $identifiables): array;
}
