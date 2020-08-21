<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait TimestampTrait
{
    /**
     * @Assert\NotBlank
     * @ORM\Column(name="created_at", type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="last_updated_at", type="datetime")
     */
    private DateTime $lastUpdatedAt;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $date): self
    {
        $this->createdAt = $date;

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

    public function setLastUpdatedAt(DateTime $date): self
    {
        $this->lastUpdatedAt = $date;

        return $this;
    }

    public function updateLastUpdatedAt(): self
    {
        $this->lastUpdatedAt = new DateTime();

        return $this;
    }
}
