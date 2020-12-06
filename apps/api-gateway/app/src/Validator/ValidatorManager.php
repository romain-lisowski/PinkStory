<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidatorManager implements ValidatorManagerInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($value, array $constraints = null, array $groups = null): void
    {
        $errors = $this->validator->validate($value, $constraints, $groups);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }
    }
}
