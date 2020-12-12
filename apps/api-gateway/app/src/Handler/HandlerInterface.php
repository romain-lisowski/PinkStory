<?php

declare(strict_types=1);

namespace App\Handler;

interface HandlerInterface
{
    public function setHandlerable(HandlerableInterface $handlerable): self;

    public function handle();
}
