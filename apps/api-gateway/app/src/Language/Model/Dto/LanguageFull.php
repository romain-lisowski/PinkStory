<?php

declare(strict_types=1);

namespace App\Language\Model\Dto;

use Symfony\Component\Serializer\Annotation as Serializer;

final class LanguageFull extends Language
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $title;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $locale;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $imageUrl;

    public function __construct(string $id = '', string $title = '', string $locale = '')
    {
        parent::__construct($id);

        $this->title = $title;
        $this->locale = $locale;
        $this->imageUrl = '';
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}
