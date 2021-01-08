<?php

declare(strict_types=1);

namespace App\Handler;

use ReflectionClass;

trait HandlerableTrait
{
    public function getHandler(): string
    {
        $class = (new ReflectionClass($this))->getName();

        return $class.'Handler';
    }
}
