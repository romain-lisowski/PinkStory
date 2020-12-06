<?php

declare(strict_types=1);

namespace App\Validator;

use App\Exception\HasErrorsExceptionInterface;
use App\Exception\HasErrorsExceptionTrait;
use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Traversable;

final class ValidatorException extends Exception implements HasErrorsExceptionInterface
{
    use HasErrorsExceptionTrait;

    protected $message = 'validator.exception';

    public function __construct(ConstraintViolationListInterface $errors)
    {
        parent::__construct($this->message);

        $this->populateErrors($errors);
    }

    public function populateErrors(Traversable $errors): void
    {
        foreach ($errors as $error) {
            $this->errors[$error->getPropertyPath()] = $error->getMessage();
        }
    }
}
