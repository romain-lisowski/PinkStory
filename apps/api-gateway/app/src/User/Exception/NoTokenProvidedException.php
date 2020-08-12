<?php

declare(strict_types=1);

namespace App\User\Exception;

use Exception;

final class NoTokenProvidedException extends Exception
{
    protected $message = 'user.exception.no_token_provided';
}
