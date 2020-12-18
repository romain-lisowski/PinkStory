<?php

declare(strict_types=1);

namespace App\Story\Query;

use App\Form\FormableInterface;
use App\Form\FormableTrait;
use App\Handler\HandlerableInterface;
use App\Handler\HandlerableTrait;
use App\Query\PaginableInterface;
use App\Query\PaginableTrait;
use App\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryImageSearchQuery implements QueryInterface, HandlerableInterface, FormableInterface, PaginableInterface
{
    use HandlerableTrait;
    use FormableTrait;
    use PaginableTrait;

    /**
     * @Assert\NotNull
     */
    public array $storyThemeIds = [];

    /**
     * @Assert\NotBlank
     */
    public string $languageId = '';
}
