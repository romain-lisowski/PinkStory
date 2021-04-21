<?php

declare(strict_types=1);

namespace App\Common\Domain\File;

final class ImageUploadException extends RuntimeException
{
    protected $message = 'file.exception.image_upload';
}
