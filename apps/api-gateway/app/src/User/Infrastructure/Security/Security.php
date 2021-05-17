<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Security;

use App\User\Domain\Security\SecurityInterface;
use App\User\Query\Model\UserCurrent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class Security implements SecurityInterface
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getUser(): ?UserCurrent
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
