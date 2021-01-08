<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use App\File\Model\Dto\ImageableTrait;
use App\File\Model\ImageableInterface;
use App\Model\Dto\DtoInterface;
use App\Model\Dto\IdentifiableInterface;
use App\Model\Dto\IdentifiableTrait;

class StoryImage implements DtoInterface, IdentifiableInterface, ImageableInterface
{
    use IdentifiableTrait;
    use ImageableTrait;

    public function __construct(string $id = '')
    {
        $this->id = $id;
    }

    public function getImageBasePath(): string
    {
        return 'story';
    }
}
