<?php

declare(strict_types=1);

namespace App\Story\Command;

use App\Command\CommandInterface;
use App\Form\FormableInterface;
use App\Form\FormableTrait;
use App\Handler\HandlerableInterface;
use App\Handler\HandlerableTrait;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryUpdateCommand implements CommandInterface, HandlerableInterface, FormableInterface
{
    use HandlerableTrait;
    use FormableTrait;

    /**
     * @Assert\NotBlank
     */
    public string $id = '';

    /**
     * @Assert\NotBlank
     */
    public string $title = '';

    /**
     * @Assert\NotBlank
     */
    public string $content = '';

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 140
     * )
     */
    public string $extract = '';

    public ?string $storyImageId = null;

    /**
     * @Assert\NotBlank
     */
    public string $languageId = '';

    /**
     * @Assert\NotNull
     */
    public array $storyThemeIds = [];
}
