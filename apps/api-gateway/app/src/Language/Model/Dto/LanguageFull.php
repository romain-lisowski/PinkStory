<?php

declare(strict_types=1);

namespace App\Language\Model\Dto;

use Symfony\Component\Serializer\Annotation as Serializer;

final class LanguageFull extends Language
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    public string $id;

    /**
     * @Serializer\Groups({"serializer"})
     */
    public string $title;

    /**
     * @Serializer\Groups({"serializer"})
     */
    public string $locale;

    /**
     * @Serializer\Groups({"serializer"})
     */
    public string $imageUrl;

    public function __construct(string $id = '', string $title = '', string $locale = '')
    {
        $this->id = $id;
        $this->title = $title;
        $this->locale = $locale;
        $this->imageUrl = '';
    }
}
