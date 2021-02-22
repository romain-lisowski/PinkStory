<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Serializer\Normalizer;

use App\Common\Domain\Validator\ConstraintViolation;
use App\Common\Domain\Validator\ValidationFailedException;

final class ValidationFailedExceptionNormalizer extends ThrowableNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($exception, string $format = null, array $context = [])
    {
        return array_merge(
            parent::normalize($exception, $format, $context),
            ['violations' => array_map(function (ConstraintViolation $violation) {
                return [
                    'property_path' => $violation->getPropertyPath(),
                    'message' => $this->translator->trans($violation->getMessage()),
                ];
            }, $exception->getViolations())]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof ValidationFailedException;
    }
}
