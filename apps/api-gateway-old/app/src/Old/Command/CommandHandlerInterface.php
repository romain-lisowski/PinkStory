<?php

declare(strict_types=1);

namespace App\Command;

use App\Handler\HandlerInterface;

interface CommandHandlerInterface extends HandlerInterface
{
    public function setCommand(CommandInterface $command): self;
}
