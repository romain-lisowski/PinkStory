<?php

declare(strict_types=1);

namespace App\Common\Domain\Model;

interface EditableInterface
{
    public const CREATE = 'CREATE';
    public const READ = 'READ';
    public const UPDATE = 'UPDATE';
    public const DELETE = 'DELETE';

    public function getEditable(): bool;

    public function setEditable(bool $editable): self;
}
