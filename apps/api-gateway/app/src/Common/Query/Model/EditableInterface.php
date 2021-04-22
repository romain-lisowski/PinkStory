<?php

declare(strict_types=1);

namespace App\Common\Query\Model;

use App\Common\Domain\Model\EditableInterface as DomainEditableInterface;

interface EditableInterface extends DomainEditableInterface
{
    public function getEditable(): bool;

    public function setEditable(bool $editable): self;
}
