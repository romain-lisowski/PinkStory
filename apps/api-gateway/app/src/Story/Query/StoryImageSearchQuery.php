<?php

declare(strict_types=1);

namespace App\Story\Query;

use App\Form\FormableInterface;
use App\Form\FormableTrait;
use App\Handler\HandlerableInterface;
use App\Handler\HandlerableTrait;
use App\Query\QueryInterface;

final class StoryImageSearchQuery implements QueryInterface, HandlerableInterface, FormableInterface
{
    use FormableTrait;
    use HandlerableTrait;
}
