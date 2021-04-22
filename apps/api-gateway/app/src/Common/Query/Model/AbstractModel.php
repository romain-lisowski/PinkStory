<?php

declare(strict_types=1);

namespace App\Common\Query\Model;

abstract class AbstractModel implements EditableInterface
{
    private bool $editable = false;

    public function getEditable(): bool
    {
        return $this->editable;
    }

    public function setEditable(bool $editable): self
    {
        $this->editable = $editable;

        return $this;
    }
}
