<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Model\ModelInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

abstract class AbstractModelNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    abstract public function normalizeModel($model, string $format = null, array $context = []): void;

    public function normalize($object, string $format = null, array $context = [])
    {
        if (!$object instanceof ModelInterface) {
            return false;
        }

        if (false === empty($context[static::class.'.used'])) {
            $context[static::class.'.used'] = [];
        }

        $context[static::class.'.used'][] = $object;

        $this->normalizeModel($object, $format, $context);

        return $this->normalizer->normalize($object, $format, $context);
    }

    abstract public function supportsNormalizationModel($model, string $format = null, array $context = []): bool;

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        if (!$data instanceof ModelInterface) {
            return false;
        }

        // Make sure we're not called twice
        if (
            false === empty($context[static::class.'.used'])
            && true === is_array($context[static::class.'.used'])
            && in_array($data, $context[static::class.'.used'])
        ) {
            return false;
        }

        return $this->supportsNormalizationModel($data, $format, $context);
    }
}
