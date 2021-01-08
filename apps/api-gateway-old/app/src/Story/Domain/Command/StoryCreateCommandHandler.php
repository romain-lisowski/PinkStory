<?php

declare(strict_types=1);

namespace App\Story\Domain\Command;

use App\Story\Domain\Event\StoryCreatedEvent;
use LogicException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class StoryCreateCommandHandler implements MessageHandlerInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->bus = $eventBus;
    }

    public function __invoke(StoryCreateCommand $command)
    {
        //throw new LogicException();
        dump($command);
        exit;

        $this->bus->dispatch(new StoryCreatedEvent('id', $command));
    }
}
