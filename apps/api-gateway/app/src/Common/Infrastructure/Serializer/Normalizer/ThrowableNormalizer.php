<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Serializer\Normalizer;

use ReflectionClass;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\String\UnicodeString;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

class ThrowableNormalizer implements NormalizerInterface
{
    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($throwable, string $format = null, array $context = [])
    {
        return [
            'type' => (new UnicodeString((new ReflectionClass($throwable))->getShortName()))->snake()->toString(),
            'message' => $this->translator->trans($throwable->getMessage()),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Throwable;
    }
}
