<?php

declare(strict_types=1);

namespace App\Message;

abstract class AbstractEntityMessage implements EntityMessageInterface
{
    use EntityMessageTrait;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
