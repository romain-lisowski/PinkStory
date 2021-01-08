<?php

declare(strict_types=1);

namespace App\Story\Exception;

use Exception;

final class StoryThemeDepthException extends Exception
{
    protected $message = 'story.exception.story_theme_depth';
}
