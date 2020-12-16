<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\Language\Model\Dto\Language;
use Symfony\Component\Serializer\Annotation as Serializer;

final class UserMedium extends User
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $name;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $nameSlug;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private Language $language;

    public function __construct(string $id = '', bool $imageDefined = false, string $name = '', string $nameSlug = '', Language $language)
    {
        parent::__construct($id, $imageDefined);

        $this->user = $this;
        $this->name = $name;
        $this->nameSlug = $nameSlug;
        $this->language = $language;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNameSlug(): string
    {
        return $this->nameSlug;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }
}
