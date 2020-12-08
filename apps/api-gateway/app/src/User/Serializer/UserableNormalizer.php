<?php

declare(strict_types=1);

namespace App\User\Serializer;

use App\User\Entity\UserableInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class UserableNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = self::class.'.used';

    private AuthorizationCheckerInterface $authorizationChecker;
    private TokenStorageInterface $tokenStorage;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    public function normalize($userable, string $format = null, array $context = [])
    {
        $currentUser = null;

        $token = $this->tokenStorage->getToken();

        if (null !== $token) {
            $currentUser = $token->getUser();
        }

        $userable->setCanUpdate($currentUser, $this->authorizationChecker);
        $userable->setCanRemove($currentUser, $this->authorizationChecker);

        $context[self::ALREADY_CALLED] = true;

        return $this->normalizer->normalize($userable, $format, $context);
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        // Make sure we're not called twice
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof UserableInterface;
    }
}
