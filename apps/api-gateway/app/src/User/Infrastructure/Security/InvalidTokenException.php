<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Security;

final class InvalidTokenException extends RuntimeException
{
    protected $message = 'user.security.token_authenticator.exception.invalid_token';
}
