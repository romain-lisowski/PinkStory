<?php

declare(strict_types=1);

namespace App\Language\Model\Dto;

use App\File\Model\Dto\ImageableTrait;
use App\File\Model\ImageableInterface;
use App\Language\Model\LanguageInterface;
use App\Model\Dto\DtoInterface;
use App\Model\Dto\IdentifiableInterface;
use App\Model\Dto\IdentifiableTrait;

class Language implements DtoInterface, LanguageInterface, IdentifiableInterface, ImageableInterface
{
    use IdentifiableTrait;
    use ImageableTrait;

    public function __construct(string $id = '')
    {
        $this->id = $id;
    }

    public function getImageBasePath(): string
    {
        return 'language';
    }
}
