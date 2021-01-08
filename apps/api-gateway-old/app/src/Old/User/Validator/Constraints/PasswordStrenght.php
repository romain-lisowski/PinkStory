<?php

declare(strict_types=1);

namespace App\User\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class PasswordStrenght extends Constraint
{
    public $message = 'user.validator.password_strenght';
}
