<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation as Serializer;

final class StoryImageFull extends StoryImageMedium
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    private Collection $storyThemes;

    public function __construct(string $id = '', string $title = '', string $titleSlug = '')
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
