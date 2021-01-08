<?php

declare(strict_types=1);

namespace App\Command;

use App\Handler\AbstractHandler;

abstract class AbstractCommandHandler extends AbstractHandler implements CommandHandlerInterface
{
    protected CommandInterface $command;

    public function setCommand(CommandInterface $command): self
    {
        $this->setHandlerable($command);

        $this->command = $command;

        return $this;
    }
}
