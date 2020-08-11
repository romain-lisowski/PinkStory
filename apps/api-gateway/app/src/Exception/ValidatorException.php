<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidatorException extends Exception
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

    public function getErrors(): array
    {
        return $this->errors;
    }
}
