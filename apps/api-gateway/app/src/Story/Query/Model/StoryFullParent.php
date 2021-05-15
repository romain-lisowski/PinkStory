<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class StoryFullParent extends StoryFull
{
    private Collection $children;

    public function __construct()
    {
        parent::__construct();

        $this->children = new ArrayCollection();
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function setChildren(Collection $children): self
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }

        return $this;
    }

    public function addChild(Story $story): self
    {
        $this->children[] = $story;

        return $this;
    }
}
