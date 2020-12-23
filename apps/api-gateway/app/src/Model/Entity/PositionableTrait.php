<?php

declare(strict_types=1);

namespace App\Model\Entity;

use App\Exception\PositionUpdateException;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait PositionableTrait
{
    /**
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private ?int $position;

    public function initPosition(?Collection $positionnedItems = null): self
    {
        $this->updatePosition(1);

        if (null !== $positionnedItems && $positionnedItems->count() > 0) {
            $positions = array_map(function ($positionnedItem) {
                return $positionnedItem->getPosition();
            }, $positionnedItems->toArray());

            rsort($positions);

            $this->updatePosition($positions[0] + 1);
        }

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function updatePosition(?int $position): self
    {
        $this->setPosition($position);

        return $this;
    }

    public static function resetPosition(Collection $positionnedItems): void
    {
        $position = 1;
        $positionnedItems = $positionnedItems->toArray();

        usort($positionnedItems, function (PositionableInterface $positionnedItem1, PositionableInterface $positionnedItem2) {
            return $positionnedItem1->getPosition() > $positionnedItem2->getPosition();
        });

        array_map(function (PositionableInterface $positionnedItem) use (&$position) {
            $positionnedItem->updatePosition($position);
            ++$position;
        }, $positionnedItems);
    }

    public static function updatePositions(Collection $positionnedItems, Collection $newPositionnedItemIds): void
    {
        foreach ($positionnedItems as $positionnedItem) {
            if (!$positionnedItem instanceof PositionableInterface || !$positionnedItem instanceof IdentifiableInterface) {
                throw new PositionUpdateException();
            }

            $position = 1;
            $foundItem = false;

            foreach ($newPositionnedItemIds as $newPositionnedItemId) {
                if ($positionnedItem->getId() === (string) $newPositionnedItemId) {
                    $positionnedItem->updatePosition($position);

                    $foundItem = true;

                    break;
                }

                ++$position;
            }

            if (false === $foundItem) {
                throw new PositionUpdateException();
            }
        }
    }
}
