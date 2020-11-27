<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

final class ImageUploadException extends Exception
{
    protected $message = 'exception.image_upload';
}
