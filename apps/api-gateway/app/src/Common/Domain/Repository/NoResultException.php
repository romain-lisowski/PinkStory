<?php

declare(strict_types=1);

namespace App\Common\Domain\Repository;

final class NoResultException extends RuntimeException
{
    protected $message = 'repository.exception.no_result_exception';

    public function __construct(\Throwable $e)
    {
        parent::__construct($this->message, $e->getCode(), $e);
    }
}
