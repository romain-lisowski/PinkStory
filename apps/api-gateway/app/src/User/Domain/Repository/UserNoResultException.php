<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\Common\Domain\Repository\NoResultException;

final class UserNoResultException extends NoResultException
{
    protected $message = 'user.repository.exception.no_result_exception';
}
