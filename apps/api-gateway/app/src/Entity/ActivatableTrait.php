<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait ActivatableTrait
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

    public function activate(): self
    {
        $this->setActivated(true);

        return $this;
    }

    public function deactivate(): self
    {
        $this->setActivated(false);

        return $this;
    }
}
