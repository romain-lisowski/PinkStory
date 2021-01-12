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
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Validator\Constraints as Assert;

final class StorySearchQuery implements QueryInterface, HandlerableInterface, FormableInterface, PaginableInterface
{
    use HandlerableTrait;
    use FormableTrait;
    use PaginableTrait;

    public const TYPE_MIXED = 'TYPE_MIXED';
    public const TYPE_PARENT = 'TYPE_PARENT';
    public const TYPE_CHILD = 'TYPE_CHILD';
    public const ORDER_POPULAR = 'ORDER_POPULAR';
    public const ORDER_CREATED_AT = 'ORDER_CREATED_AT';

    /**
     * @Assert\NotNull
     */
    public array $storyThemeIds = [];

    /**
     * @Assert\NotBlank
     */
    public string $languageId = '';

    /**
     * @Assert\NotNull
     */
    public array $readingLanguageIds = [];

    public ?string $userId = null;

    /**
     * @Assert\NotBlank
     */
    public string $type = self::TYPE_MIXED;

    /**
     * @Assert\NotBlank
     */
    public string $order = self::ORDER_POPULAR;

    /**
     * @Assert\NotBlank
     */
    public string $sort = Criteria::DESC;
}
