<?php

declare(strict_types=1);

namespace App\Common\Query\Model;

use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Model\EditableTrait;

abstract class AbstractModel implements EditableInterface
{
    use EditableTrait;
}
