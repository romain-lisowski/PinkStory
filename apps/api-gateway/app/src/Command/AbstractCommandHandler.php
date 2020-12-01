<?php

declare(strict_types=1);

namespace App\Command;

use InvalidArgumentException;
use ReflectionClass;

abstract class AbstractCommandHandler implements CommandHandlerInterface
{
    protected CommandInterface $command;

    public function setCommand(CommandInterface $command): self
    {
        $commandHandlerClass = (new ReflectionClass($this))->getName();
        $commandClass = preg_replace('/^(.+)Handler$/', '$1', $commandHandlerClass);

        if ($commandClass !== (new ReflectionClass($command))->getName()) {
            throw new InvalidArgumentException();
        }

        $this->command = $command;

        return $this;
    }

    abstract public function handle();
}
