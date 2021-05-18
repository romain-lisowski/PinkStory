<?php

declare(strict_types=1);

namespace App\Story\Domain\Model;

use App\Common\Domain\Model\RuntimeException;

class StoryThemeDepthException extends RuntimeException
{
    protected $message = 'story_theme.model.exception.child_depth';
}
