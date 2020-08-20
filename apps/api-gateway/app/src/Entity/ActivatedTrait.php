<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait ActivatedTrait
{
    /**
     * @Assert\NotNull
     * @ORM\Column(name="activated", type="boolean")
     */
    private bool $activated;

    public function isActivated(): bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }
}