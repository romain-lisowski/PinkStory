<?php

declare(strict_types=1);

namespace App\Presentation\Response;

use Exception;

final class ResponderException extends Exception
{
    protected $message = 'responder.exception';
}
