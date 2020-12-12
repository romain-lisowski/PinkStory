<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Model\EditableInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class EditableNormalizer extends AbstractEntityNormalizer
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private TokenStorageInterface $tokenStorage;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    public function normalizeEntity($editable, string $format = null, array $context = []): void
    {
        if (!$editable instanceof EditableInterface) {
            return;
        }

        $currentUser = null;

        $token = $this->tokenStorage->getToken();

        if (null !== $token) {
            $currentUser = $token->getUser();
        }

        $editable->setEditable($this->authorizationChecker, $currentUser);
    }

    public function supportsNormalizationEntity($editable, string $format = null, array $context = []): bool
    {
        return $editable instanceof EditableInterface;
    }
}
