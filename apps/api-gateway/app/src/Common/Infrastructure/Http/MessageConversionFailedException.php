<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Http;

use Exception;
use Throwable;

final class MessageConversionFailedException extends Exception
{
    protected $message = 'http.message_param_converter.exception.message_conversion_failed';

    public function __construct(Throwable $e)
    {
        parent::__construct($this->message, 0, $e);
    }
}
