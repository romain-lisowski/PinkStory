<?php

declare(strict_types=1);

namespace App\Validator;

interface ValidatorManagerInterface
{
    /**
     * Validates a value.
     *
     * @param mixed $value
     *
     * @throws ValidatorException
     */
    public function validate($value, array $constraints = null, array $groups = null): void;
}
