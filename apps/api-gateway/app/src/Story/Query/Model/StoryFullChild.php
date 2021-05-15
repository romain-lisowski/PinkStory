<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

final class StoryFullChild extends StoryFull
{
    private Story $parent;
    private ?Story $previous;
    private ?Story $next;

    public function __construct()
    {
        parent::__construct();

        $this->previous = null;
        $this->next = null;
    }

    public function getParent(): Story
    {
        return $this->parent;
    }

    public function setParent(Story $story): self
    {
        $this->parent = $story;

        return $this;
    }

    public function getPrevious(): ?Story
    {
        return $this->previous;
    }

    public function setPrevious(?Story $story): self
    {
        $this->previous = $story;

        return $this;
    }

    public function getNext(): ?Story
    {
        return $this->next;
    }

    public function setNext(?Story $story): self
    {
        $this->next = $story;

        return $this;
    }
}
