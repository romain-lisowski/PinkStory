<?php

declare(strict_types=1);

namespace App\Common\Domain\Repository;

class NoResultException extends RuntimeException
{
    protected $message = 'repository.exception.no_result_exception';

    public function __construct(?\Throwable $e = null)
    {
        if (null !== $e) {
            parent::__construct($this->message, $e->getCode(), $e);
        } else {
            parent::__construct($this->message);
        }
    }
}
