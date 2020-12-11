<?php

declare(strict_types=1);

namespace App\Story\Dto;

class StoryImage implements IdentifiableInterface
{
    use IdentifiableTrait;

    public function __construct(string $id = '')
    {
        $this->id = $id;
    }
}
