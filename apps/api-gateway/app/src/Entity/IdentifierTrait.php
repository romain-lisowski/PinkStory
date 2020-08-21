<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

trait IdentifierTrait
{
    /**
     * @Groups({"detail", "list"})
     * @Assert\NotBlank
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
        $this->setId(Uuid::v4()->toRfc4122());

        return $this;
    }
}
