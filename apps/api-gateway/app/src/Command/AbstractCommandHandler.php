<?php

declare(strict_types=1);

namespace App\Command;

use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractCommandHandler implements CommandHandlerInterface
{
    protected CommandInterface $command;
    protected ?UserInterface $currentUser;

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

    public function setCurrentUser(?UserInterface $currentUser): self
    {
        $this->currentUser = $currentUser;

        return $this;
    }

    abstract public function handle();
}
