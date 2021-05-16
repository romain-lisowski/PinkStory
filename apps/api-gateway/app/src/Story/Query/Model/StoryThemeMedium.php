<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

class StoryThemeMedium extends StoryTheme
{
    private string $title;
    private string $titleSlug;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitleSlug(): string
    {
        return $this->titleSlug;
    }

    public function setTitleSlug(string $titleSlug): self
    {
        $this->titleSlug = $titleSlug;

        return $this;
    }
}
