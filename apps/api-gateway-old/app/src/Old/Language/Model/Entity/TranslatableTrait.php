<?php

declare(strict_types=1);

namespace App\Language\Model\Entity;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

trait TranslatableTrait
{
    /**
     * @Assert\NotBlank
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private string $reference;

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function updateReference(string $reference): self
    {
        $this->setReference($reference);

        return $this;
    }

    abstract public function getTranslations(): Collection;
}
