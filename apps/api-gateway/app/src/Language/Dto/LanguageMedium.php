<?php

declare(strict_types=1);

namespace App\Language\Dto;

use Symfony\Component\Serializer\Annotation as Serializer;

final class LanguageMedium
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    public string $imageUrl;
}
