<?php

declare(strict_types=1);

namespace App\Story\Query\Query;

use App\Common\Query\Query\PaginableInterface;
use App\Common\Query\Query\QueryInterface;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Validator\Constraints as Assert;

final class StorySearchQuery implements QueryInterface, PaginableInterface
{
    public const TYPE_MIXED = 'TYPE_MIXED';
    public const TYPE_PARENT = 'TYPE_PARENT';
    public const TYPE_CHILD = 'TYPE_CHILD';
    public const ORDER_POPULAR = 'ORDER_POPULAR';
    public const ORDER_CREATED_AT = 'ORDER_CREATED_AT';

    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $languageId;

    /**
     * @Assert\NotNull
     */
    private array $readingLanguageIds;

    /**
     * @Assert\NotNull
     */
    private array $storyThemeIds;

    private ?string $userId;

    /**
     * @Assert\NotBlank
     */
    private string $type;

    /**
     * @Assert\NotBlank
     */
    private string $order;

    /**
     * @Assert\NotBlank
     */
    private string $sort;

    /**
     * @Assert\NotBlank
     */
    private int $limit;

    /**
     * @Assert\NotBlank
     */
    private int $offset;

    public function __construct(string $languageId, array $readingLanguageIds = [], array $storyThemeIds = [], ?string $userId = null, string $type = self::TYPE_MIXED, string $order = self::ORDER_POPULAR, string $sort = Criteria::DESC, int $limit = PaginableInterface::LIMIT, int $offset = PaginableInterface::OFFSET)
    {
        $this->languageId = $languageId;
        $this->readingLanguageIds = $readingLanguageIds;
        $this->storyThemeIds = $storyThemeIds;
        $this->userId = $userId;
        $this->type = $type;
        $this->order = $order;
        $this->sort = $sort;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getLanguageId(): string
    {
        return $this->languageId;
    }

    public function getReadingLanguageIds(): array
    {
        return $this->readingLanguageIds;
    }

    public function getStoryThemeIds(): array
    {
        return $this->storyThemeIds;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function getSort(): string
    {
        return $this->sort;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}
