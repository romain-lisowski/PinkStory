<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

class StoryFull extends StoryMedium
{
    private string $content;

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
