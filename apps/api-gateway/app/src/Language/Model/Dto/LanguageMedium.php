<?php

declare(strict_types=1);

namespace App\Language\Model\Dto;

use Symfony\Component\Serializer\Annotation as Serializer;

final class LanguageMedium extends Language
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    public string $imageUrl;
}
