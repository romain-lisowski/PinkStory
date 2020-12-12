<?php

declare(strict_types=1);

namespace App\User\Entity;

use App\Model\EditableInterface;

interface UserEditableInterface extends EditableInterface
{
    public function getUser(): User;

    public function setUser(User $user): self;
}
