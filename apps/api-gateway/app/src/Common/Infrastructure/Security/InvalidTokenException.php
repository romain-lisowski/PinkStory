<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Security;

use Exception;

final class InvalidTokenException extends Exception
{
    protected $message = 'security.token_authenticator.exception.invalid_token';
}
