<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Security\Core\User\UserInterface;

interface CommandHandlerInterface
{
    public function setCommand(CommandInterface $command): self;

    public function setCurrentUser(?UserInterface $currentUser): self;

    public function handle();
}
