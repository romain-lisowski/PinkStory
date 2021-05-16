<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\Common\Domain\Repository\NoResultException;

final class AccessTokenNoResultException extends NoResultException
{
    protected $message = 'access_token.repository.exception.no_result_exception';
}
