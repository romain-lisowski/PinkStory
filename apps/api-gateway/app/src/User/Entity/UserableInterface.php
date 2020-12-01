<?php

declare(strict_types=1);

namespace App\User\Entity;

interface UserableInterface
{
    public function getUser(): User;

    public function setUser(User $user): self;
}
