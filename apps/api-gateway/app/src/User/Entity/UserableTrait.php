<?php

declare(strict_types=1);

namespace App\User\Entity;

trait UserableTrait
{
    private User $user;

    public function getUser(): User
    {
        return $this->user;
    }

    abstract public function setUser(User $user): self;
}
