<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

interface UserableInterface
{
    public function getUser(): UserInterface;
}
