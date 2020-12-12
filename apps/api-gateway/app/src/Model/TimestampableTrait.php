<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;

trait TimestampableTrait
{
    private DateTime $createdAt;

    private DateTime $lastUpdatedAt;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getLastUpdatedAt(): DateTime
    {
        return $this->lastUpdatedAt;
    }
}
