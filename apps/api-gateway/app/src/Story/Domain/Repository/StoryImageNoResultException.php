<?php

declare(strict_types=1);

namespace App\Story\Domain\Repository;

use App\Common\Domain\Repository\NoResultException;

final class StoryImageNoResultException extends NoResultException
{
    protected $message = 'story_image.repository.exception.no_result_exception';
}
