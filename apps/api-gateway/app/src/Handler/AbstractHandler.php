<?php

declare(strict_types=1);

namespace App\Handler;

use InvalidArgumentException;
use ReflectionClass;

abstract class AbstractHandler implements HandlerInterface
{
    protected HandlerableInterface $handlerable;

    public function setHandlerable(HandlerableInterface $handlerable): self
    {
        if ($handlerable->getHandler() !== (new ReflectionClass($this))->getName()) {
            throw new InvalidArgumentException();
        }

        $this->handlerable = $handlerable;

        return $this;
    }

    abstract public function handle();
}
