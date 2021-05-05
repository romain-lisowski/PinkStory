<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class StoryImageFull extends StoryImageMedium
{
    private Collection $storyThemes;

    public function __construct(string $id, string $title, string $titleSlug)
    {
        parent::__construct($id, $title, $titleSlug);

        $this->storyThemes = new ArrayCollection();
    }

    public function getStoryThemes(): Collection
    {
        return $this->storyThemes;
    }

    public function addStoryTheme(StoryTheme $storyTheme): self
    {
        $this->storyThemes[] = $storyTheme;

        return $this;
    }
}
