<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class Entity extends Constraint
{
    public string $message = 'model.validator.constraint.entity_not_found';
    public string $entityClass;
    public string $expr = 'repository.findOne(value)';
    public string $valueType = 'uuid';
}
