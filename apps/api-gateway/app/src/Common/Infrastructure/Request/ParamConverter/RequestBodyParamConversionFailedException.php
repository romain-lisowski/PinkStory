<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Request\ParamConverter;

final class RequestBodyParamConversionFailedException extends RuntimeException
{
    protected $message = 'request.param_converter.request_body.exception.request_body_param_conversion_failed';
}
