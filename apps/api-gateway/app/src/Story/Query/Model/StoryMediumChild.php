<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

final class StoryMediumChild extends StoryMedium
{
    private Story $parent;

    public function getParent(): Story
    {
        return $this->parent;
    }

    public function setParent(Story $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
}
