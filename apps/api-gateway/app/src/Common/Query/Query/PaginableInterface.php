<?php

declare(strict_types=1);

namespace App\Common\Query\Query;

interface PaginableInterface
{
    public const LIMIT = 24;
    public const OFFSET = 0;

    public function getLimit(): int;

    public function getOffset(): int;
}
