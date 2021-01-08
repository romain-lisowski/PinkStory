<?php

declare(strict_types=1);

namespace App\Handler;

interface HandlerableInterface
{
    public function getHandler(): string;
}
