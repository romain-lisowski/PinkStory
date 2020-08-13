<?php

declare(strict_types=1);

namespace App\Exception;

interface HasErrorsExceptionInterface
{
    public function hasErrors(): bool;

    public function getErrors(): array;
}
