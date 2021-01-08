<?php

declare(strict_types=1);

namespace App\Domain\Validator;

interface ValidatorInterface
{
    /**
     * Validates a value.
     *
     * @param mixed $value
     *
     * @throws ValidationException
     */
    public function validate($value, array $constraints = null, array $groups = null): void;
}
