<?php

declare(strict_types=1);

namespace App\Form;

final class NotSubmittedFormException extends FormException
{
    protected $message = 'form.exception.not_submitted_form';
}
