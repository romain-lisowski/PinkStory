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

    public function __construct()
    {
        // init values
        $this->storyThemeIds = [];
        $this->userId = null;
        $this->type = self::TYPE_MIXED;
        $this->order = self::ORDER_POPULAR;
        $this->sort = Criteria::DESC;
        $this->limit = PaginableInterface::LIMIT;
        $this->offset = PaginableInterface::OFFSET;
    }

    public function getLanguageId(): string
    {
        return $this->languageId;
    }

    public function setLanguageId(string $languageId): self
    {
        $this->languageId = $languageId;

        return $this;
    }

    public function getReadingLanguageIds(): array
    {
        return $this->readingLanguageIds;
    }

    public function setReadingLanguageIds(array $readingLanguageIds): self
    {
        $this->readingLanguageIds = $readingLanguageIds;

        return $this;
    }

    public function getStoryThemeIds(): array
    {
        return $this->storyThemeIds;
    }

    public function setStoryThemeIds(array $storyThemeIds): self
    {
        $this->storyThemeIds = $storyThemeIds;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function setOrder(string $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getSort(): string
    {
        return $this->sort;
    }

    public function setSort(string $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }
}
