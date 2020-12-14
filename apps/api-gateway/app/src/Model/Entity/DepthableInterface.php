<?php

declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\DepthableInterface as ModelDepthableInterface;

interface DepthableInterface extends ModelDepthableInterface
{
    public function getParent(): ?DepthableInterface;

    public function setParent(?DepthableInterface $parent): self;

    public function updateParent(?DepthableInterface $parent): self;

    public function removeChild(DepthableInterface $child): self;
}
