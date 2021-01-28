<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Security;

use App\Common\Domain\Security\AuthorizationCheckerInterface as DomainAuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class AuthorizationChecker implements DomainAuthorizationCheckerInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function isGranted(string $attribute, $subject): void
    {
        if (false === $this->authorizationChecker->isGranted($attribute, $subject)) {
            throw new AccessDeniedException();
        }
    }
}
