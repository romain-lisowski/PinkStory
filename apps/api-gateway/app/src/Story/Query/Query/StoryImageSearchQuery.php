<?php

declare(strict_types=1);

namespace App\Story\Query\Query;

use App\Common\Query\Query\PaginableInterface;
use App\Common\Query\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryImageSearchQuery implements QueryInterface, PaginableInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $languageId;

    /**
     * @Assert\NotNull
     */
    private array $storyThemeIds;

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

    public function getStoryThemeIds(): array
    {
        return $this->storyThemeIds;
    }

    public function setStoryThemeIds(array $storyThemeIds): self
    {
        $this->storyThemeIds = $storyThemeIds;

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
