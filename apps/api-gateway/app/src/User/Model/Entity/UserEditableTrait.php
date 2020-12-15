<?php

declare(strict_types=1);

namespace App\User\Model\Entity;

use App\User\Model\UserEditableTrait as ModelUserEditableTrait;
use App\User\Model\UserInterface;

trait UserEditableTrait
{
    use ModelUserEditableTrait;

    abstract public function setUser(UserInterface $user): self;
}
