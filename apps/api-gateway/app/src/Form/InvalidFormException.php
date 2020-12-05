<?php

declare(strict_types=1);

namespace App\Form;

use App\Exception\HasErrorsExceptionInterface;
use App\Exception\HasErrorsExceptionTrait;
use Symfony\Component\Form\FormErrorIterator;
use Traversable;

final class InvalidFormException extends FormException implements HasErrorsExceptionInterface
{
    use HasErrorsExceptionTrait;

    protected $message = 'form.exception.invalid_form';

    public function __construct(FormErrorIterator $errors)
    {
        parent::__construct($this->message);

        $this->populateErrors($errors);
    }

    public function populateErrors(Traversable $errors): void
    {
        foreach ($errors as $error) {
            $this->errors[$error->getOrigin()->getName()] = $error->getMessage();
        }
    }
}
