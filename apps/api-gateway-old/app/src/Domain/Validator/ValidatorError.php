<?php

declare(strict_types=1);

namespace App\Domain\Validator;

final class ValidatorError
{
    private string $propertyPath;
    private string $message;

    public function __construct(string $propertyPath, string $message)
    {
        $this->propertyPath = $propertyPath;
        $this->message = $message;
    }

    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
