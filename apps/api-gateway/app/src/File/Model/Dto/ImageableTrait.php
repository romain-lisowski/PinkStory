<?php

declare(strict_types=1);

namespace App\File\Model\Dto;

use App\File\Model\ImageableTrait as ModelImageableTrait;
use Symfony\Component\Serializer\Annotation as Serializer;

trait ImageableTrait
{
    use ModelImageableTrait;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private ?string $imageUrl = null;
}
