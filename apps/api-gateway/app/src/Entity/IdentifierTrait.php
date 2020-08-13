<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

trait IdentifierTrait
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="uuid", type="string", length=255, unique=true)
     */
    private string $uuid;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function generateUuid(): self
    {
        $this->uuid = Uuid::v4()->toRfc4122();

        return $this;
    }
}
