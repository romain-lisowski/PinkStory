<?php

declare(strict_types=1);

namespace App\Language\Model\Dto;

use Symfony\Component\Serializer\Annotation as Serializer;

final class LanguageMedium extends Language
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $imageUrl;

    public function __construct(string $id = '')
    {
        parent::__construct($id);

        $this->imageUrl = '';
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}
