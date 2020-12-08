<?php

declare(strict_types=1);

namespace App\User\Serializer;

use App\Serializer\AbstractEntityNormalizer;
use App\User\Entity\UserableInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserableNormalizer extends AbstractEntityNormalizer
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private TokenStorageInterface $tokenStorage;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    public function normalizeEntity($userable, string $format = null, array $context = []): void
    {
        if (!$userable instanceof UserableInterface) {
            return;
        }

        $currentUser = null;

        $token = $this->tokenStorage->getToken();

        if (null !== $token) {
            $currentUser = $token->getUser();
        }

        $userable->setUpdatable($currentUser, $this->authorizationChecker);
        $userable->setDeletable($currentUser, $this->authorizationChecker);
    }

    public function supportsNormalizationEntity($userable, string $format = null, array $context = []): bool
    {
        return $userable instanceof UserableInterface;
    }
}
