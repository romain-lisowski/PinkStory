<?php

declare(strict_types=1);

namespace App\Model\Dto;

use App\Model\EditableTrait as ModelEditableTrait;
use Symfony\Component\Serializer\Annotation as Serializer;

trait EditableTrait
{
    use ModelEditableTrait;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private bool $editable = false;
}
