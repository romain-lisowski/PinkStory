<?php

declare(strict_types=1);

namespace App\Exception;

use Traversable;

interface HasErrorsExceptionInterface
{
    public function hasErrors(): bool;

    public function getErrors(): array;

    public function populateErrors(Traversable $errors): void;
}
