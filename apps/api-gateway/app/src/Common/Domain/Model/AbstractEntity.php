<?php

declare(strict_types=1);

namespace App\Common\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass
 * @UniqueEntity(
 *      fields = {"id"}
 * )
 */
abstract class AbstractEntity implements EditableInterface
{
    use EditableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="uuid", unique=true)
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $id;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\NotBlank
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(name="last_updated_at", type="datetime")
     * @Assert\NotBlank
     */
    private \DateTime $lastUpdatedAt;

    public function __construct()
    {
        // init values
        $this->generateId()
            ->setCreatedAt(new \DateTime())
            ->updateLastUpdatedAt()
        ;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getLastUpdatedAt(): \DateTime
    {
        return $this->lastUpdatedAt;
    }

    public function updateLastUpdatedAt(): self
    {
        $this->setLastUpdatedAt(new \DateTime());

        return $this;
    }

    private function generateId(): self
    {
        $this->setId(Uuid::v4()->toRfc4122());

        return $this;
    }

    private function setCreatedAt(\DateTime $date): self
    {
        $this->createdAt = $date;

        return $this;
    }

    private function setLastUpdatedAt(\DateTime $date): self
    {
        $this->lastUpdatedAt = $date;

        return $this;
    }
}
