<?php

declare(strict_types=1);

namespace App\User\Message;

use App\Message\AbstractEntityMessage;
use App\Message\SyncMessageInterface;

final class UserRegenerateEmailValidationCodeMessage extends AbstractEntityMessage implements SyncMessageInterface
{
}
