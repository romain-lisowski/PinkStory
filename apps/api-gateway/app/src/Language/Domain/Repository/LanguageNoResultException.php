<?php

declare(strict_types=1);

namespace App\Language\Domain\Repository;

use App\Common\Domain\Repository\NoResultException as DomainNoResultException;

final class LanguageNoResultException extends DomainNoResultException
{
    protected $message = 'language.repository.exception.no_result_exception';
}
