<?php

declare(strict_types=1);

namespace App\Message;

trait EntityMessageTrait
{
    private string $id;

    public function getId(): string
    {
        return $this->id;
    }
}
