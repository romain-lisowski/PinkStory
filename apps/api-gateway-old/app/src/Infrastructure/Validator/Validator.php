<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator;

use App\Domain\Validator\ValidatorError;
use App\Domain\Validator\ValidatorException;
use App\Domain\Validator\ValidatorInterface as DomainValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class Validator implements DomainValidatorInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($value, array $constraints = null, array $groups = null): void
    {
        $constraintViolationList = $this->validator->validate($value, $constraints, $groups);

        if (count($constraintViolationList) > 0) {
            $errors = [];

            foreach ($constraintViolationList as $constraintViolation) {
                $errors[] = new ValidatorError($constraintViolation->getPropertyPath(), $constraintViolation->getMessage());
            }

            throw new ValidatorException($errors);
        }
    }
}
