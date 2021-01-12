<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

final class PositionUpdateException extends Exception
{
    protected $message = 'exception.position_update';
}
