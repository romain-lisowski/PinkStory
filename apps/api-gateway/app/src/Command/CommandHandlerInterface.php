<?php

declare(strict_types=1);

namespace App\Command;

interface CommandHandlerInterface
{
    public function setCommand(CommandInterface $command): self;

    public function handle();
}
