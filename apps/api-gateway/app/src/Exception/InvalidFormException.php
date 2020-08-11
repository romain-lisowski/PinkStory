<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\Form\FormErrorIterator;

final class InvalidFormException extends Exception
{
    protected $message = 'exception.invalid_form';
    private array $errors = [];

    public function __construct(FormErrorIterator $errors)
    {
        foreach ($errors as $error) {
            $this->errors[$error->getOrigin()->getName()] = $error->getMessage();
        }

        parent::__construct($this->message);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
