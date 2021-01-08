<?php

declare(strict_types=1);

namespace App\User\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class Email extends Constraint
{
    public $message = 'user.validator.invalid_email';
}
