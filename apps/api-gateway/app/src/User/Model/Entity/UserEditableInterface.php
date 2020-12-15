<?php

declare(strict_types=1);

namespace App\User\Model\Entity;

use App\User\Model\UserEditableInterface as ModelUserEditableInterface;
use App\User\Model\UserInterface;

interface UserEditableInterface extends ModelUserEditableInterface
{
    public function setUser(UserInterface $user): self;
}
