<?php

declare(strict_types=1);

namespace App\User\Entity;

use App\Entity\EditableTrait;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

trait UserEditableTrait
{
    use EditableTrait;

    private User $user;

    /**
     * Need to set here cause annotations are not keep between traits inheritance.
     *
     * @Serializer\Groups({"serializer"})
     */
    private bool $editable = false;

    public function getUser(): User
    {
        return $this->user;
    }

    abstract public function setUser(User $user): self;

    public function setEditable(?AuthorizationCheckerInterface $authorizationChecker = null, ?UserInterface $currentUser = null): self
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
}
