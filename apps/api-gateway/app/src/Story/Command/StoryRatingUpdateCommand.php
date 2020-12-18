<?php

declare(strict_types=1);

namespace App\Story\Command;

use App\Command\CommandInterface;
use App\Form\FormableInterface;
use App\Form\FormableTrait;
use App\Handler\HandlerableInterface;
use App\Handler\HandlerableTrait;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryRatingUpdateCommand implements CommandInterface, HandlerableInterface, FormableInterface
{
    use HandlerableTrait;
    use FormableTrait;

    /**
     * @Assert\NotBlank
     */
    public string $storyId = '';

    /**
     * @Assert\NotBlank
     */
    public string $userId = '';

    /**
     * @Assert\NotBlank
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     * )
     */
    public int $rate = 5;
}
