<?php

declare(strict_types=1);

namespace App\Entity;

interface ActivateInterface
{
    public function isActivated(): bool;

    public function setActivated(bool $activated): self;

    public function activate(): self;

    public function deactivate(): self;
}
