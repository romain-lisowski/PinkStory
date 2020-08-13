<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait TimestampTrait
{
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="last_updated_at", type="datetime")
     */
    private DateTime $lastUpdatedAt;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function initCreatedAt(): self
    {
        $this->createdAt = new DateTime();

        return $this;
    }

    public function getLastUpdatedAt(): DateTime
    {
        return $this->lastUpdatedAt;
    }

    public function setLastUpdatedAt(DateTime $lastUpdatedAt): self
    {
        $this->lastUpdatedAt = $lastUpdatedAt;

        return $this;
    }

    public function updateLastUpdatedAt(): self
    {
        $this->lastUpdatedAt = new DateTime();

        return $this;
    }
}
