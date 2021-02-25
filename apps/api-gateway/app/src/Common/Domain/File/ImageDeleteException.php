<?php

declare(strict_types=1);

namespace App\Common\Domain\File;

use Exception;
use Throwable;

final class ImageDeleteException extends Exception
{
    protected $message = 'file.exception.image_delete';

    public function __construct(Throwable $e)
    {
        parent::__construct($this->message, $e->getCode(), $e);
    }
}
