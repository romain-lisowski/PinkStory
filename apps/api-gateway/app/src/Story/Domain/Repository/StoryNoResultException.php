<?php

declare(strict_types=1);

namespace App\Story\Domain\Repository;

use App\Common\Domain\Repository\NoResultException;

final class StoryNoResultException extends NoResultException
{
    protected $message = 'story.repository.exception.no_result_exception';
}
