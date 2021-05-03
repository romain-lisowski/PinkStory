<?php

declare(strict_types=1);

namespace App\Common\Domain\Model;

use Doctrine\Common\Collections\Collection;

interface PositionableInterface
{
    public function getPosition(): ?int;

    public function setPosition(?int $position): self;

    public function updatePosition(?int $position): self;

    public static function initPosition(PositionableInterface $positionable, Collection $positionedItems): void;

    public static function resetPositions(Collection $positionedItems): void;

    /**
     * @throws PositionUpdateException
     */
    public static function updatePositions(Collection $positionedItems, array $newPositionedItemIds): void;
}
