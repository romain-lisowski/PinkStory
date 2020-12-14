<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\Common\Collections\Collection;

interface DepthableInterface
{
    public function getChildren(): Collection;

    public function addChild(DepthableInterface $child): self;
}
