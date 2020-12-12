<?php

declare(strict_types=1);

namespace App\Model;

trait IdentifiableTrait
{
    private string $id;

    public function getId(): string
    {
        return $this->id;
    }
}
