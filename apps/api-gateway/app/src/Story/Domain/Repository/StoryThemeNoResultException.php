<?php

declare(strict_types=1);

namespace App\Story\Domain\Repository;

use App\Common\Domain\Repository\NoResultException;

final class StoryThemeNoResultException extends NoResultException
{
    protected $message = 'story_theme.repository.exception.no_result_exception';
}
