<?php

declare(strict_types=1);

namespace App\Language\Query\Model;

final class Language
{
    private string $id;
    private string $title;
    private string $locale;

    public function __construct(string $id, string $title, string $locale)
    {
        $this->id = $id;
        $this->title = $title;
        $this->locale = $locale;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
