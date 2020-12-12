<?php

declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\TimestampableTrait as ModelTimestampableTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait TimestampableTrait
{
    use ModelTimestampableTrait;

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

    public function setCreatedAt(DateTime $date): self
    {
        $this->createdAt = $date;

        return $this;
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
