<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;

trait DepthableTrait
{
    private Collection $children;

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(DepthableInterface $child): self
    {
        if (!$child instanceof self) {
            throw new InvalidArgumentException();
        }

        $this->children[] = $child;

        return $this;
    }
}
