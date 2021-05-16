<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception;

class RuntimeException extends \RuntimeException
{
    protected $message = '';

    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct($this->message, null !== $previous ? $previous->getCode() : 0, $previous);
    }
}
