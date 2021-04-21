<?php

declare(strict_types=1);

namespace App\Common\Domain\Security;

class AccessDeniedException extends RuntimeException
{
    protected $message = 'security.exception.access_denied';
}
