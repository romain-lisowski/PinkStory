<?php

declare(strict_types=1);

namespace App\Common\Query\Query;

interface QueryBusInterface
{
    /**
     * @return mixed
     */
    public function dispatch(QueryInterface $query);
}
