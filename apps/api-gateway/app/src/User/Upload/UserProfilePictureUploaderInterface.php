<?php

declare(strict_types=1);

namespace App\User\Upload;

use App\Upload\UploaderInterface;
use App\User\Entity\User;

interface UserProfilePictureUploaderInterface extends UploaderInterface
{
    public function setUser(User $user): self;
}
