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

    public function __construct(string $languageId, array $storyThemeIds = [], int $limit = PaginableInterface::LIMIT, int $offset = PaginableInterface::OFFSET)
    {
        $this->languageId = $languageId;
        $this->storyThemeIds = $storyThemeIds;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getLanguageId(): string
    {
        return $this->languageId;
    }

    public function getStoryThemeIds(): array
    {
        return $this->storyThemeIds;
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
