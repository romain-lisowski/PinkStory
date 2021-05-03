<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

class StoryThemeMedium extends StoryTheme
{
    private string $title;
    private string $titleSlug;

    public function __construct(string $id, string $title, string $titleSlug)
    {
        parent::__construct($id);

        $this->title = $title;
        $this->titleSlug = $titleSlug;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTitleSlug(): string
    {
        return $this->titleSlug;
    }
}
