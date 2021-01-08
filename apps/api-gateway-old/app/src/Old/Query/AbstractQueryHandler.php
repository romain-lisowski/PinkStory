<?php

declare(strict_types=1);

namespace App\Query;

use App\Handler\AbstractHandler;

abstract class AbstractQueryHandler extends AbstractHandler implements QueryHandlerInterface
{
    protected QueryInterface $query;

    public function setQuery(QueryInterface $query): self
    {
        $this->setHandlerable($query);

        $this->query = $query;

        return $this;
    }
}
