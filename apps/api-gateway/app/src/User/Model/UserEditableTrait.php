<?php

declare(strict_types=1);

namespace App\User\Model;

use App\Model\EditableTrait;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

trait UserEditableTrait
{
    use EditableTrait;

    private UserInterface $user;

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    abstract public function setUser(UserInterface $user): self;

    public function setEditable(?AuthorizationCheckerInterface $authorizationChecker = null, ?UserInterface $currentUser = null): self
    {
        // moderators are like small gods
        if (null !== $authorizationChecker && true === $authorizationChecker->isGranted(UserRole::ROLE_MODERATOR)) {
            $this->editable = true;
        }

        if (null !== $currentUser && $this->getUser()->getId() === $currentUser->getId()) {
            $this->editable = true;
        }

        return $this;
    }
}
