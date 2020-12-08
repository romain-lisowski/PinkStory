<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait TimestampableTrait
{
    /**
     * @Serializer\Groups({"serializer"})
     * @Assert\NotBlank
     * @ORM\Column(name="created_at", type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @Serializer\Groups({"serializer"})
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
        $this->setLastUpdatedAt(new DateTime());

        return $this;
    }
}
