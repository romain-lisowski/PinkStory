<?php

declare(strict_types=1);

namespace App\Story\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryCreateCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     */
    private string $title;

    /**
     * @Assert\NotBlank
     */
    private string $content;

    /**
     * @Assert\NotBlank
     */
    private string $extract;

    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $userId;

    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $languageId;

    /**
     * @Assert\Uuid
     */
    private ?string $parentId;

    /**
     * @Assert\Uuid
     */
    private ?string $storyImageId;

    /**
     * @Assert\NotNull
     */
    private array $storyThemeIds;

    public function __construct(string $title, string $content, string $extract, string $userId, string $languageId, ?string $parentId = null, ?string $storyImageId = null, array $storyThemeIds = [])
    {
        $this->title = $title;
        $this->content = $content;
        $this->extract = $extract;
        $this->userId = $userId;
        $this->languageId = $languageId;
        $this->parentId = $parentId;
        $this->storyImageId = $storyImageId;
        $this->storyThemeIds = $storyThemeIds;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getExtract(): string
    {
        return $this->extract;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getLanguageId(): string
    {
        return $this->languageId;
    }

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    public function getStoryImageId(): ?string
    {
        return $this->storyImageId;
    }

    public function getStoryThemeIds(): array
    {
        return $this->storyThemeIds;
    }
}
