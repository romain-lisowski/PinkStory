<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Command;

use App\Common\Domain\Command\CommandBusInterface;
use App\Common\Domain\Command\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class CommandBus implements CommandBusInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->bus = $commandBus;
    }

    public function dispatch(CommandInterface $command): array
    {
        $envelope = $this->bus->dispatch($command);

        // get the value that was returned by the last message handler
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp->getResult();
    }
}
