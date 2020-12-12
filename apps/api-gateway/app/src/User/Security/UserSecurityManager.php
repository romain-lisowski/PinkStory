<?php

declare(strict_types=1);

namespace App\User\Security;

use App\User\Model\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class UserSecurityManager implements UserSecurityManagerInterface
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getUser(): ?User
    {
        $token = $this->tokenStorage->getToken();

        if (null === $token) {
            return null;
        }

        $user = $token->getUser();

        if (!\is_object($user)) {
            return null;
        }

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }
}
