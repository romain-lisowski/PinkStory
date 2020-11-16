<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;

interface TimestampInterface
{
    public function getCreatedAt(): DateTime;

    public function setCreatedAt(DateTime $date): self;

    public function getLastUpdatedAt(): DateTime;

    public function setLastUpdatedAt(DateTime $date): self;

    public function updateLastUpdatedAt(): self;
}
