<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Http;

use Exception;
use Throwable;

final class RequestBodyParamConversionFailedException extends Exception
{
    protected $message = 'http.request_body_param_converter.exception.request_body_param_conversion_failed';

    public function __construct(Throwable $e)
    {
        parent::__construct($this->message, 0, $e);
    }
}
