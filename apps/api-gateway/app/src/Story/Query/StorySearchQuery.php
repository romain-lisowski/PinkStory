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
     * @Assert\NotBlank
     */
    public array $readingLanguageIds = [];

    /**
     * @Assert\NotBlank
     */
    public string $order = self::ORDER_POPULAR;

    /**
     * @Assert\NotBlank
     */
    public string $sort = Criteria::DESC;
}
