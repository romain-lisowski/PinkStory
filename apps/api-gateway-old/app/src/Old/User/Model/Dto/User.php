<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\File\Model\Dto\ImageableTrait;
use App\File\Model\ImageableInterface;
use App\Model\Dto\DtoInterface;
use App\Model\Dto\IdentifiableInterface;
use App\Model\Dto\IdentifiableTrait;
use App\User\Model\UserInterface;

class User implements DtoInterface, UserInterface, IdentifiableInterface, ImageableInterface
{
    use IdentifiableTrait;
    use ImageableTrait;

    private bool $imageDefined;

    public function __construct(string $id = '', bool $imageDefined = false)
    {
        $this->id = $id;
        $this->imageDefined = $imageDefined;
    }

    public function hasImage(): bool
    {
        return $this->imageDefined;
    }

    public function getImageBasePath(): string
    {
        return 'user';
    }
}
