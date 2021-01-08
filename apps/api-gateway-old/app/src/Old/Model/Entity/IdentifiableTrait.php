<?php

declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\IdentifiableTrait as ModelIdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

trait IdentifiableTrait
{
    use ModelIdentifiableTrait;

    /**
     * @Assert\NotBlank
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid", unique=true)
     */
    private string $id;

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
