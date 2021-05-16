<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Serializer\Normalizer;

use App\Common\Domain\Model\EditableInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

final class EditableNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($editable, string $format = null, array $context = [])
    {
        // avoid circular reference
        if (false === isset($context[static::class.'.used'])) {
            $context[static::class.'.used'] = [];
        }
        $context[static::class.'.used'][] = $editable;

        $editable->setEditable($this->authorizationChecker->isGranted(EditableInterface::UPDATE, $editable));

        return $this->normalizer->normalize($editable, $format, $context);
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

        return $data instanceof EditableInterface;
    }
}
