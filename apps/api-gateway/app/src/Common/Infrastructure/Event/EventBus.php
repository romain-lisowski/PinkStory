<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Event;

use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Event\EventInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

final class EventBus implements EventBusInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->bus = $eventBus;
    }

    public function dispatch(EventInterface $event): void
    {
        try {
            $this->bus->dispatch($event);
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious();
        }
    }
}
