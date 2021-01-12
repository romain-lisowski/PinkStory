<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use Symfony\Component\Serializer\Annotation as Serializer;

class StoryMediumChild extends StoryMedium
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    private StoryMedium $parent;

    public function getParent(): StoryMedium
    {
        return $this->parent;
    }

    public function setParent(StoryMedium $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
}
