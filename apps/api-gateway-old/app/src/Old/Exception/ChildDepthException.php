<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

final class ChildDepthException extends Exception
{
    protected $message = 'exception.child_depth';
}
