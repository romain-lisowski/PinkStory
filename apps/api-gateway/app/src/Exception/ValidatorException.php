<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidatorException extends Exception implements HasErrorsExceptionInterface
{
    protected $message = 'exception.validator';
    private array $errors = [];

    public function __construct(ConstraintViolationListInterface $errors)
    {
        foreach ($errors as $error) {
            $this->errors[$error->getPropertyPath()] = $error->getMessage();
        }

        parent::__construct($this->message);
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
