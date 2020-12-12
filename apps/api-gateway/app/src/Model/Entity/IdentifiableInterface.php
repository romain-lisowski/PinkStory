<?php

declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\IdentifiableInterface as ModelIdentifiableInterface;

interface IdentifiableInterface extends ModelIdentifiableInterface
{
    public function setId(string $id): self;

    public function generateId(): self;
}
