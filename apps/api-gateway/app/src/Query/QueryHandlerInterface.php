<?php

declare(strict_types=1);

namespace App\Query;

use App\Handler\HandlerInterface;

interface QueryHandlerInterface extends HandlerInterface
{
    public function setQuery(QueryInterface $command): self;
}
