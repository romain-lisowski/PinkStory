<?php

declare(strict_types=1);

namespace App\User\Exception;

use Exception;

final class InvalidTokenException extends Exception
{
    protected $message = 'user.exception.invalid_token';
}
