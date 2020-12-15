<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation as Serializer;

final class StoryThemeFullParent extends StoryThemeFull
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    private Collection $children;

    public function __construct(string $id = '', string $title = '', string $titleSlug = '')
    {
        parent::__construct($id, $title, $titleSlug);

        $this->children = new ArrayCollection();
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(StoryThemeFull $child): self
    {
        $this->children[] = $child;

        return $this;
    }
}
