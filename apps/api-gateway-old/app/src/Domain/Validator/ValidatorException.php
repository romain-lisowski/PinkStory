<?php

declare(strict_types=1);

namespace App\Domain\Validator;

use Exception;

final class ValidatorException extends Exception
{
    protected $message = 'validator.exception';

    private array $errors = [];

    public function __construct(array $errors)
    {
        parent::__construct($this->message);

        $this->error = $errors;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
