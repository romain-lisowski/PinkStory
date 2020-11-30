<?php

declare(strict_types=1);

namespace App\Language\Entity;

use Doctrine\Common\Collections\Collection;

interface TranslatableInterface
{
    public function getReference(): string;

    public function setReference(string $reference): self;

    public function updateReference(string $reference): self;

    public function getTranslations(): Collection;
}
