<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\File;

use App\Common\Domain\File\ImageableInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

final class ImageableNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($imageable, string $format = null, array $context = [])
    {
        // avoid circular reference
        if (false === isset($context[static::class.'.used'])) {
            $context[static::class.'.used'] = [];
        }
        $context[static::class.'.used'][] = $imageable;

        $imageable->setImageUrl($this->params->get('project_image_storage_dsn'));

        return $this->normalizer->normalize($imageable, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        // avoid circular reference
        if (
            false === empty($context[static::class.'.used'])
            && true === is_array($context[static::class.'.used'])
            && in_array($data, $context[static::class.'.used'])
        ) {
            return false;
        }

        return $data instanceof ImageableInterface;
    }
}
