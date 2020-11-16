<?php

declare(strict_types=1);

namespace App\Entity;

interface IdentifierInterface
{
    public function getId(): string;

    public function setId(string $id): self;

    public function generateId(): self;
}
