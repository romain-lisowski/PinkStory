<?php

declare(strict_types=1);

namespace App\Common\Domain\File;

final class ImageDeleteException extends RuntimeException
{
    protected $message = 'file.exception.image_delete';
}
