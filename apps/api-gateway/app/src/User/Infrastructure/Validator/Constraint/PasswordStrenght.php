<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class PasswordStrenght extends Constraint
{
    public string $message = 'user.validator.constraint.password_strenght_min';
}
