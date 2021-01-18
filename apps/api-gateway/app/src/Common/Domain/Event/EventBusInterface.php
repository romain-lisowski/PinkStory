<?php

declare(strict_types=1);

namespace App\Common\Domain\Event;

interface EventBusInterface
{
    public function dispatch(EventInterface $event): void;
}
