<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Infrastructure\Messenger\CommandHandlerInterface;

final class UserCreateCommandHandler implements CommandHandlerInterface
{
    public function __invoke(UserCreateCommand $command)
    {
        dump($command);
        exit;
    }
}
