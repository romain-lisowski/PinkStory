<?php

declare(strict_types=1);

namespace App\Story\Domain\Model;

use App\Common\Domain\Model\ChildDepthException;

class StoryThemeDepthException extends ChildDepthException
{
    protected $message = 'story_theme.model.exception.child_depth';
}
