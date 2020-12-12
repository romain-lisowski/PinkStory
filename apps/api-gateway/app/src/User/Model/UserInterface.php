<?php

declare(strict_types=1);

namespace App\User\Model;

use App\Model\ModelInterface;

interface UserInterface extends ModelInterface
{
    public function getId(): string;
}
