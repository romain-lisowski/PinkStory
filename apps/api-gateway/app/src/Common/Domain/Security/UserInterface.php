<?php

declare(strict_types=1);

namespace App\Common\Domain\Security;

interface UserInterface
{
    public function getId(): string;
}
