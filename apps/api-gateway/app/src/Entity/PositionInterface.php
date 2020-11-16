<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface PositionInterface
{
    public function initPosition(?Collection $positionnedItems = null): self;

    public function getPosition(): int;

    public function setPosition(int $position): self;

    public function updatePosition(int $position): self;

    public static function resetPosition(Collection $positionnedItems): void;
}
