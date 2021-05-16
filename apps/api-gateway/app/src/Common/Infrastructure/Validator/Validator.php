<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Validator;

use App\Common\Domain\Validator\ConstraintViolation;
use App\Common\Domain\Validator\ValidationFailedException;
use App\Common\Domain\Validator\ValidatorInterface as DomainValidatorInterface;
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
            $violations = [];

            foreach ($constraintViolationList as $constraintViolation) {
                $violations[] = new ConstraintViolation($constraintViolation->getPropertyPath(), $constraintViolation->getMessage());
            }

            throw new ValidationFailedException($violations);
        }
    }
}
