<?php

declare(strict_types=1);

namespace App\User\Exception;

use Exception;

final class UserProfilePictureUploadException extends Exception
{
    protected $message = 'user.exception.profile_picture_upload';
}
