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
    private bool $updatable = false;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private bool $deletable = false;

    public function getUser(): User
    {
        return $this->user;
    }

    abstract public function setUser(User $user): self;

    public function getUpdatable(): bool
    {
        return $this->updatable;
    }

    public function setUpdatable(?UserInterface $currentUser = null, ?AuthorizationCheckerInterface $authorizationChecker = null): self
    {
        // moderators are like small gods
        if (null !== $authorizationChecker && true === $authorizationChecker->isGranted(UserRole::ROLE_MODERATOR)) {
            $this->updatable = true;
        }

        if (null !== $currentUser && $this->getUser() === $currentUser) {
            $this->updatable = true;
        }

        return $this;
    }

    public function getDeletable(): bool
    {
        return $this->deletable;
    }

    public function setDeletable(?UserInterface $currentUser = null, ?AuthorizationCheckerInterface $authorizationChecker = null): self
    {
        // moderators are like small gods
        if (null !== $authorizationChecker && true === $authorizationChecker->isGranted(UserRole::ROLE_MODERATOR)) {
            $this->deletable = true;
        }

        if (null !== $currentUser && $this->getUser() === $currentUser) {
            $this->deletable = true;
        }

        return $this;
    }
}
