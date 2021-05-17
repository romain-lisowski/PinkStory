<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

final class StoryMediumParent extends StoryMedium
{
    private int $childrenTotal;

    public function __construct()
    {
        parent::__construct();

        // init values
        $this->childrenTotal = 0;
    }

    public function getChildrenTotal(): int
    {
        return $this->childrenTotal;
    }

    public function setChildrenTotal(int $childrenTotal): self
    {
        $this->childrenTotal = $childrenTotal;

        return $this;
    }
}
