<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Query;

use App\Common\Query\Query\QueryBusInterface;
use App\Common\Query\Query\QueryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class QueryBus implements QueryBusInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->bus = $queryBus;
    }

    public function dispatch(QueryInterface $query): array
    {
        $envelope = $this->bus->dispatch($query);

        // get the value that was returned by the last message handler
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp->getResult();
    }
}
