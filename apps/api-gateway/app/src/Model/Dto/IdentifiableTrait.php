<?php

declare(strict_types=1);

namespace App\Model\Dto;

use App\Model\IdentifiableTrait as ModelIdentifiableTrait;
use Symfony\Component\Serializer\Annotation as Serializer;

trait IdentifiableTrait
{
    use ModelIdentifiableTrait;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $id;
}
