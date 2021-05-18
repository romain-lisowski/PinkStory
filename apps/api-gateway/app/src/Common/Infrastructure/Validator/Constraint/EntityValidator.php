<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Validator\Constraint;

use App\Common\Domain\Repository\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Uuid as ConstraintUuid;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class EntityValidator extends ConstraintValidator
{
    private ManagerRegistry $registry;
    private ExpressionLanguage $language;

    public function __construct(ManagerRegistry $registry, ExpressionLanguage $language)
    {
        $this->registry = $registry;
        $this->language = $language;
    }

    public function validate($value, Constraint $constraint): void
    {
        try {
            if (!$constraint instanceof Entity) {
                throw new UnexpectedTypeException($constraint, Entity::class);
            }

            // custom constraints should ignore null and empty values to allow
            // other constraints (NotBlank, NotNull, etc.) take care of that
            if (null === $value || '' === $value) {
                return;
            }

            $values = (true === is_array($value)) ? $value : [$value];

            // if is uuid, value needs to be a valid uuid
            if ('uuid' === $constraint->valueType) {
                foreach ($values as $value) {
                    if (false === Uuid::isValid($value)) {
                        $this->context->buildViolation((new ConstraintUuid())->message)
                            ->addViolation()
                        ;

                        return;
                    }
                }
            }

            $repository = $this->registry->getManagerForClass($constraint->entityClass)->getRepository($constraint->entityClass);

            foreach ($values as $value) {
                $this->language->evaluate($constraint->expr, [
                    'repository' => $repository,
                    'value' => $value,
                ]);
            }
        } catch (NoResultException $e) {
            $this->context->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
