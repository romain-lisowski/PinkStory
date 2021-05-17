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

    public function __construct()
    {
        // init values
        $this->parentId = null;
        $this->storyImageId = null;
        $this->storyThemeIds = [];
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getExtract(): string
    {
        return $this->extract;
    }

    public function setExtract(string $extract): self
    {
        $this->extract = $extract;

        return $this;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
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

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    public function setParentId(?string $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getStoryImageId(): ?string
    {
        return $this->storyImageId;
    }

    public function setStoryImageId(?string $storyImageId): self
    {
        $this->storyImageId = $storyImageId;

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
}
