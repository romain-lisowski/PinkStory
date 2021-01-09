<?php

declare(strict_types=1);

namespace App\Common\Domain\Validator;

use Exception;

final class ValidationFailedException extends Exception
{
    protected $message = 'validator.exception.validation_failed';

    private array $violations = [];

    public function __construct(array $violations)
    {
        parent::__construct($this->message);

        $this->violations = $violations;
    }

    public function hasViolations(): bool
    {
        return count($this->violations) > 0;
    }

    public function getViolations(): array
    {
        return $this->violations;
    }
}
