<?php

declare(strict_types=1);

namespace App\Common\Domain\Validator;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     *
     * @throws ValidationFailedException
     */
    public function validate($value, array $constraints = null, array $groups = null): void;
}
