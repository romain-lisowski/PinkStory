<?php

declare(strict_types=1);

namespace App\User\Command;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final class UserUpdateProfilePictureCommand
{
    /**
     * @Assert\NotBlank
     */
    public string $id;

    /**
     * @Assert\NotNull
     * @Assert\File(
     *      mimeTypes = {"image/jpeg", "image/png"},
     * )
     */
    public UploadedFile $profilePicture;
}
