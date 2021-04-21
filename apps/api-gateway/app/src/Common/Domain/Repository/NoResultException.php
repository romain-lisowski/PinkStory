<?php

declare(strict_types=1);

namespace App\Common\Domain\Repository;

class NoResultException extends RuntimeException
{
    protected $message = 'repository.exception.no_result_exception';
}
