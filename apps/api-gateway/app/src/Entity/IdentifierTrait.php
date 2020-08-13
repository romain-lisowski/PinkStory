<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

trait IdentifierTrait
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid", unique=true)
     */
    private string $id;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function generateId(): self
    {
        $this->id = Uuid::v4()->toRfc4122();

        return $this;
    }
}
