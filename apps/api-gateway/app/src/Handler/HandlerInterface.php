<?php

declare(strict_types=1);

namespace App\Handler;

use Symfony\Component\Security\Core\User\UserInterface;

interface HandlerInterface
{
    public function setHandlerable(HandlerableInterface $handlerable): self;

    public function setCurrentUser(?UserInterface $currentUser): self;

    public function handle();
}
