<?php

declare(strict_types=1);

namespace App\User\Entity;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

trait UserableTrait
{
    private User $user;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private bool $canUpdate = false;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private bool $canRemove = false;

    public function getUser(): User
    {
        return $this->user;
    }

    abstract public function setUser(User $user): self;

    public function getCanUpdate(): bool
    {
        return $this->canUpdate;
    }

    public function setCanUpdate(?UserInterface $currentUser = null, ?AuthorizationCheckerInterface $authorizationChecker = null): self
    {
        // moderators are like small gods
        if (null !== $authorizationChecker && true === $authorizationChecker->isGranted(UserRole::ROLE_MODERATOR)) {
            $this->canUpdate = true;
        }

        if (null !== $currentUser && $this->getUser() === $currentUser) {
            $this->canUpdate = true;
        }

        return $this;
    }

    public function getCanRemove(): bool
    {
        return $this->canRemove;
    }

    public function setCanRemove(?UserInterface $currentUser = null, ?AuthorizationCheckerInterface $authorizationChecker = null): self
    {
        // moderators are like small gods
        if (null !== $authorizationChecker && true === $authorizationChecker->isGranted(UserRole::ROLE_MODERATOR)) {
            $this->canRemove = true;
        }

        if (null !== $currentUser && $this->getUser() === $currentUser) {
            $this->canRemove = true;
        }

        return $this;
    }
}
