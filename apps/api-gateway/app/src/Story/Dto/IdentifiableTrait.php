<?php

declare(strict_types=1);

namespace App\Story\Dto;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation as Serializer;

trait IdentifiableTrait
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    public string $id;

    public static function extractIds(Collection $identifiables): array
    {
        return array_map(function (IdentifiableInterface $identifiable) {
            return $identifiable->id;
        }, $identifiables->toArray());
    }
}
