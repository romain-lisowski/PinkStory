<?php

declare(strict_types=1);

namespace App\Model;

use App\User\Model\UserInterface;
use App\User\Model\UserRole;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

trait EditableTrait
{
    private bool $editable = false;

    public function getEditable(): bool
    {
        return $this->editable;
    }

    public function setEditable(?AuthorizationCheckerInterface $authorizationChecker = null, ?UserInterface $currentUser = null): self
    {
        // admin are like small gods
        if (null !== $authorizationChecker && true === $authorizationChecker->isGranted(UserRole::ROLE_ADMIN)) {
            $this->editable = true;
        }

        return $this;
    }
}
