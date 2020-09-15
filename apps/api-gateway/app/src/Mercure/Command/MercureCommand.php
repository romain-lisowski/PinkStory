<?php

declare(strict_types=1);

namespace App\Mercure\Command;

use Symfony\Component\Validator\Constraints as Assert;

final class MercureCommand
{
    /**
     * @Assert\NotBlank
     */
    public string $message;

    public ?string $userId = null;
}
