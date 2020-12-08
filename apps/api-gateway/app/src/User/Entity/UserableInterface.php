<?php

declare(strict_types=1);

namespace App\User\Entity;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserableInterface
{
    public function getUser(): User;

    public function setUser(User $user): self;

    public function getUpdatable(): bool;

    public function setUpdatable(?UserInterface $currentUser = null, ?AuthorizationCheckerInterface $authorizationChecker = null): self;

    public function getDeletable(): bool;

    public function setDeletable(?UserInterface $currentUser = null, ?AuthorizationCheckerInterface $authorizationChecker = null): self;
}
