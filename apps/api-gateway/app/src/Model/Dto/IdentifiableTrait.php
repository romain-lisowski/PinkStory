<?php

declare(strict_types=1);

namespace App\Model\Dto;

use App\Model\IdentifiableTrait as ModelIdentifiableTrait;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation as Serializer;

trait IdentifiableTrait
{
    use ModelIdentifiableTrait;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $id;

    public static function extractIds(Collection $identifiables): array
    {
        return array_map(function (IdentifiableInterface $identifiable) {
            return $identifiable->getId();
        }, $identifiables->toArray());
    }
}
