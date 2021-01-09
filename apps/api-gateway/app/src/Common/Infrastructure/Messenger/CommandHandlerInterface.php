<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Messenger;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

interface CommandHandlerInterface extends MessageHandlerInterface
{
}
