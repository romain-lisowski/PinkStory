<?php

declare(strict_types=1);

namespace App\Language\Domain\Repository;

use App\Common\Domain\Repository\NoResultException;

class LanguageNoResultException extends NoResultException
{
    protected $message = 'language.repository.exception.no_result_exception';
}
