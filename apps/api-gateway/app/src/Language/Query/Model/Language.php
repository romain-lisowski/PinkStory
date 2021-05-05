<?php

declare(strict_types=1);

namespace App\Language\Query\Model;

use App\Common\Domain\Model\IdentifiableInterface;
use App\Common\Domain\Model\IdentifiableTrait;
use App\Common\Query\Model\AbstractModel;

class Language extends AbstractModel implements IdentifiableInterface
{
    use IdentifiableTrait;

    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
