<?php

declare(strict_types=1);

namespace App\User\Model;

use App\Model\EditableInterface;

interface UserEditableInterface extends EditableInterface
{
    public function getUser(): UserInterface;

    public function setUser(UserInterface $user): self;
}
