<?php

declare(strict_types=1);

namespace App\User\File;

use App\File\FileManagerInterface;
use App\User\Entity\User;

interface UserFileManagerInterface extends FileManagerInterface
{
    public function setUser(User $user): self;
}
