<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use App\Model\Dto\DtoInterface;
use App\Model\Dto\IdentifiableInterface;
use App\Model\Dto\IdentifiableTrait;

class Story implements DtoInterface, IdentifiableInterface
{
    use IdentifiableTrait;

    public function __construct(string $id = '')
    {
        $this->id = $id;
    }
}
