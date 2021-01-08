<?php

declare(strict_types=1);

namespace App\Story\Command;

use App\Command\CommandInterface;
use App\Handler\HandlerableInterface;
use App\Handler\HandlerableTrait;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryRatingDeleteCommand implements CommandInterface, HandlerableInterface
{
    use HandlerableTrait;

    /**
     * @Assert\NotBlank
     */
    public string $storyId = '';

    /**
     * @Assert\NotBlank
     */
    public string $userId = '';
}
