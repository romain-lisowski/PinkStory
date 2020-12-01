<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class UserRemoveImageCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     */
    public string $id;
}
