<?php

declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\TimestampableInterface as ModelTimestampableInterface;
use DateTime;

interface TimestampableInterface extends ModelTimestampableInterface
{
    public function setCreatedAt(DateTime $date): self;

    public function setLastUpdatedAt(DateTime $date): self;

    public function updateLastUpdatedAt(): self;
}
