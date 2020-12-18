<?php

declare(strict_types=1);

namespace App\Story\Query;

use App\Form\FormableInterface;
use App\Form\FormableTrait;
use App\Handler\HandlerableInterface;
use App\Handler\HandlerableTrait;
use App\Model\PaginableInterface;
use App\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryImageSearchQuery implements QueryInterface, HandlerableInterface, FormableInterface
{
    use HandlerableTrait;
    use FormableTrait;

    /**
     * @Assert\NotNull
     */
    public array $storyThemeIds = [];

    /**
     * @Assert\NotBlank
     */
    public string $languageId = '';

    /**
     * @Assert\NotBlank
     */
    public int $limit = PaginableInterface::LIMIT;

    /**
     * @Assert\NotBlank
     */
    public int $offset = PaginableInterface::OFFSET;
}
