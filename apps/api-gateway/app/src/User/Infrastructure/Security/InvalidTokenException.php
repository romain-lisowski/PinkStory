<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Security;

use Exception;

final class InvalidTokenException extends Exception
{
    protected $message = 'user.security.token_authenticator.exception.invalid_token';
}
