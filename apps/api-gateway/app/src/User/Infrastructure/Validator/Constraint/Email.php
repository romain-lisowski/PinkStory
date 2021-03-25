<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class Email extends Constraint
{
    public string $message = 'user.validator.constraint.invalid_email';
}
