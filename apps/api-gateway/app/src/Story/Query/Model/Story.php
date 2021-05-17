<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Model\EditableTrait;
use App\Common\Domain\Model\IdentifiableInterface;
use App\Common\Domain\Model\IdentifiableTrait;
use App\Common\Query\Model\AbstractModel;

class Story extends AbstractModel implements IdentifiableInterface, EditableInterface
{
    use IdentifiableTrait;
    use EditableTrait;

    private string $id;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
