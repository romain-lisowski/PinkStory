<?php

declare(strict_types=1);

namespace App\File;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class ImageableNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = self::class.'.used';

    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function normalize($imageable, string $format = null, array $context = [])
    {
        $imageable->setImageUrl($this->params->get('project_file_manager_dsn'));

        $context[self::ALREADY_CALLED] = true;

        return $this->normalizer->normalize($imageable, $format, $context);
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        // Make sure we're not called twice
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof ImageableInterface;
    }
}
