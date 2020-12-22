<?php

declare(strict_types=1);

namespace App\Story\Message;

use App\Message\AbstractEntityMessage;
use App\Message\AsyncMessageInterface;

final class StoryUpdateMessage extends AbstractEntityMessage implements AsyncMessageInterface
{
}
