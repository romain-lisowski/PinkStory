<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

final class InvalidSSLKeyException extends Exception
{
    protected $message = 'exception.invalid_ssl_key';
}
