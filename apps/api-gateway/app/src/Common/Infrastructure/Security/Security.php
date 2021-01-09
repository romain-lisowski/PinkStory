<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Security;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class Security implements SecurityInterface
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

        return $user;
    }
}
