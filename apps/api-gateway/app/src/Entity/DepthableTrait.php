<?php

declare(strict_types=1);

namespace App\Entity;

use App\Exception\ChildDepthException;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;

trait DepthableTrait
{
    private ?DepthableInterface $parent;

    private Collection $children;

    public function getParent(): ?DepthableInterface
    {
        return $this->parent;
    }

    public function setParent(?DepthableInterface $parent): self
    {
        $this->parent = $parent;

        if (null !== $parent) {
            if (!$parent instanceof self) {
                throw new InvalidArgumentException();
            }

            if (null !== $parent->getParent()) {
                throw new ChildDepthException();
            }

            if ($this instanceof PositionableInterface) {
                $this->initPosition($this->parent->getChildren());
            }

            $parent->addChild($this);
        } else {
            if ($this instanceof PositionableInterface) {
                $this->initPosition();
            }
        }

        return $this;
    }

    public function updateParent(?DepthableInterface $parent): self
    {
        if (null !== $this->parent) {
            if (!$parent instanceof self) {
                throw new InvalidArgumentException();
            }

            $this->parent->removeChild($this);
        }

        $this->setParent($parent);

        return $this;
    }

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

    public function removeChild(DepthableInterface $child): self
    {
        if (!$child instanceof self) {
            throw new InvalidArgumentException();
        }

        $this->children->removeElement($child);

        if ($this instanceof PositionableInterface) {
            static::resetPosition($this->children);
        }

        return $this;
    }
}
