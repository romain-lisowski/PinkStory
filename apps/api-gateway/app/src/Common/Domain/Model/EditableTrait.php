<?php

declare(strict_types=1);

namespace App\Common\Domain\Model;

trait EditableTrait
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
