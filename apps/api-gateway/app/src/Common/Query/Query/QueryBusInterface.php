<?php

declare(strict_types=1);

namespace App\Common\Query\Query;

interface QueryBusInterface
{
    public function dispatch(QueryInterface $query): array;
}
