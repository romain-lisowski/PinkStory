<?php

declare(strict_types=1);

namespace App\Form;

use ReflectionClass;

trait FormableTrait
{
    public function getFormType(): string
    {
        $class = (new ReflectionClass($this))->getName();

        return $class.'FormType';
    }
}
