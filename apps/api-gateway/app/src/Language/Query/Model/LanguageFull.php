<?php

declare(strict_types=1);

namespace App\Language\Query\Model;

final class LanguageFull extends Language
{
    private string $title;
    private string $locale;

    public function __construct(string $id, string $title, string $locale)
    {
        parent::__construct($id);

        $this->title = $title;
        $this->locale = $locale;
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
