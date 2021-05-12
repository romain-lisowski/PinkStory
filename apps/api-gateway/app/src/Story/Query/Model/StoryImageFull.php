<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class StoryImageFull extends StoryImageMedium
{
    private Collection $storyThemes;

    public function __construct()
    {
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
