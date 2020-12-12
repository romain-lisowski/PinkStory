<?php

declare(strict_types=1);

namespace App\User\Security;

use App\User\Model\Dto\CurrentUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class UserSecurityManager implements UserSecurityManagerInterface
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getCurrentUser(): ?CurrentUser
    {
        $token = $this->tokenStorage->getToken();

        if (null === $token) {
            return null;
        }

        $user = $token->getUser();

        if (!\is_object($user)) {
            return null;
        }

        if (!$user instanceof CurrentUser) {
            return null;
        }

        return $user;
    }
}
