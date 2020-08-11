<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractEntity
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="uuid", type="string", length=255, unique=true)
     */
    private string $uuid;

    /**
     * @ORM\Column(name="activated", type="boolean")
     */
    private bool $activated = true;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="last_updated_at", type="datetime")
     */
    private DateTime $lastUpdatedAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->lastUpdatedAt = new DateTime();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getActivated(): bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

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
}
