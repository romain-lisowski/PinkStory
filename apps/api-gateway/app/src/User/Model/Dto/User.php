<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\File\Model\Dto\ImageableTrait;
use App\File\Model\ImageableInterface;
use App\Model\Dto\DtoInterface;
use App\Model\Dto\IdentifiableTrait;
use App\Model\IdentifiableInterface;
use App\User\Model\UserInterface;

class User implements DtoInterface, UserInterface, IdentifiableInterface, ImageableInterface
{
    use IdentifiableTrait;
    use ImageableTrait;

    public function __construct(string $id = '')
    {
        $this->id = $id;
    }

    public function getImageBasePath(): string
    {
        return 'user';
    }
}
