<?php

declare(strict_types=1);

namespace App\Exception;

use Traversable;

trait HasErrorsExceptionTrait
{
    private array $errors = [];

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    abstract public function populateErrors(Traversable $errors): void;
}
