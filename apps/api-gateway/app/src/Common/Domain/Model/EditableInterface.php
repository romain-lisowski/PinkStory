<?php

declare(strict_types=1);

namespace App\Common\Domain\Model;

interface EditableInterface
{
    public const UPDATE = 'UPDATE';
    public const DELETE = 'DELETE';
}
