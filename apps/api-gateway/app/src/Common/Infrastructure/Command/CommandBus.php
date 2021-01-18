<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Command;

use App\Common\Domain\Command\CommandBusInterface;
use App\Common\Domain\Command\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class CommandBus implements CommandBusInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->bus = $commandBus;
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->bus->dispatch($command);
    }
}
