<?php

declare(strict_types=1);

namespace App\User\Entity;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserableInterface
{
    public function getUser(): User;

    public function setUser(User $user): self;

    public function getCanUpdate(): bool;

    public function setCanUpdate(?UserInterface $currentUser = null, ?AuthorizationCheckerInterface $authorizationChecker = null): self;

    public function getCanRemove(): bool;

    public function setCanRemove(?UserInterface $currentUser = null, ?AuthorizationCheckerInterface $authorizationChecker = null): self;
}
