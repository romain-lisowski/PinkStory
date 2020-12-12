<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\Common\Collections\Collection;

interface DepthableInterface
{
    public function getParent(): ?DepthableInterface;

    public function setParent(?DepthableInterface $parent): self;

    public function updateParent(?DepthableInterface $parent): self;

    public function getChildren(): Collection;

    public function addChild(DepthableInterface $child): self;

    public function removeChild(DepthableInterface $child): self;
}
