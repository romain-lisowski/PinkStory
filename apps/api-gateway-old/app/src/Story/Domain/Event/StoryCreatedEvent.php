<?php

declare(strict_types=1);

namespace App\Story\Domain\Event;

use App\Domain\Message\EventInterface;
use App\Story\Domain\Command\StoryCreateCommand;

final class StoryCreatedEvent implements EventInterface
{
    private string $id;
    private StoryCreateCommand $data;

    public function __construct(string $id, StoryCreateCommand $data)
    {
        $this->id = $id;
        $this->data = $data;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getData(): StoryCreateCommand
    {
        return $this->data;
    }
}
