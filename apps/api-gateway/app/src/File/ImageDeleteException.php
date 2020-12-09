<?php

declare(strict_types=1);

namespace App\File;

use Exception;

final class ImageDeleteException extends Exception
{
    protected $message = 'file.exception.image_delete';
}
