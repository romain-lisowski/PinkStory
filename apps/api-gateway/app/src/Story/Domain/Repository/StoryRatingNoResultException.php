<?php

declare(strict_types=1);

namespace App\Story\Domain\Repository;

use App\Common\Domain\Repository\NoResultException;

final class StoryRatingNoResultException extends NoResultException
{
    protected $message = 'story_rating.repository.exception.no_result_exception';
}
