<?php

declare(strict_types=1);

namespace App\Language\Model\Dto;

use App\Language\Model\LanguageInterface;
use App\Model\Dto\IdentifiableTrait;
use App\Model\IdentifiableInterface;

class Language implements LanguageInterface, IdentifiableInterface
{
    use IdentifiableTrait;

    public function __construct(string $id = '')
    {
        $this->id = $id;
    }
}
