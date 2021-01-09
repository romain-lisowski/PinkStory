<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Validator\Constraint;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use UnexpectedValueException;
use ZxcvbnPhp\Zxcvbn;

final class PasswordStrenghtValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PasswordStrenght) {
            throw new UnexpectedTypeException($constraint, PasswordStrenght::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (false === is_string($value)) {
            // throw this exception if your validator cannot handle
            // the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');
        }

        $zxcvbn = new Zxcvbn();

        $weak = $zxcvbn->passwordStrength($value);

        if ($weak['score'] < 3) {
            $this->context->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
