<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\AbstractEntity;
use App\Entity\IdentifiableInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

abstract class AbstractEntityNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    abstract public function normalizeEntity($entity, string $format = null, array $context = []): void;

    public function normalize($object, string $format = null, array $context = [])
    {
        if (!$object instanceof AbstractEntity) {
            return;
        }

        if (!$object instanceof IdentifiableInterface) {
            return false;
        }

        if (false === empty($context[static::class.'.used'])) {
            $context[static::class.'.used'] = [];
        }

        $context[static::class.'.used'][] = $object->getId();

        $this->normalizeEntity($object, $format, $context);

        return $this->normalizer->normalize($object, $format, $context);
    }

    abstract public function supportsNormalizationEntity($entity, string $format = null, array $context = []): bool;

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        if (!$data instanceof AbstractEntity) {
            return false;
        }

        if (!$data instanceof IdentifiableInterface) {
            return false;
        }

        // Make sure we're not called twice
        if (
            false === empty($context[static::class.'.used'])
            && true === is_array($context[static::class.'.used'])
            && in_array($data->getId(), $context[static::class.'.used'])
        ) {
            return false;
        }

        return $this->supportsNormalizationEntity($data, $format, $context);
    }
}
