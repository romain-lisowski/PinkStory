<?php

declare(strict_types=1);

namespace App\Common\Domain\Model;

use Doctrine\Common\Collections\Collection;

trait PositionableTrait
{
    public static function initPosition(PositionableInterface $positionable, Collection $positionedItems): void
    {
        $positionable->setPosition(1);

        if ($positionedItems->count() > 0) {
            $positions = array_map(function (PositionableInterface $positionedItem) {
                return $positionedItem->getPosition();
            }, $positionedItems->toArray());

            rsort($positions);

            $positionable->setPosition($positions[0] + 1);
        }
    }

    public static function resetPositions(Collection $positionedItems): void
    {
        $position = 1;
        $positionedItems = $positionedItems->toArray();

        usort($positionedItems, function (PositionableInterface $positionedItem1, PositionableInterface $positionedItem2) {
            return $positionedItem1->getPosition() > $positionedItem2->getPosition();
        });

        array_map(function (PositionableInterface $positionedItem) use (&$position) {
            $positionedItem->updatePosition($position);
            ++$position;
        }, $positionedItems);
    }

    /**
     * @throws PositionUpdateException
     */
    public static function updatePositions(Collection $positionedItems, array $newPositionedItemIds): void
    {
        foreach ($positionedItems as $positionedItem) {
            $foundItem = false;

            foreach ($newPositionedItemIds as $position => $newPositionedItemId) {
                if ($positionedItem->getId() === $newPositionedItemId) {
                    $positionedItem->updatePosition($position + 1);

                    $foundItem = true;

                    break;
                }
            }

            if (false === $foundItem) {
                throw new PositionUpdateException();
            }
        }
    }
}
