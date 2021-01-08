<?php

declare(strict_types=1);

namespace App\Story\Domain\Command;

use App\Domain\Message\CommandInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryCreateCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     */
    private string $title = '';

    /**
     * @Assert\NotBlank
     */
    private string $content = '';

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 140
     * )
     */
    private string $extract = '';

    private ?Uuid $storyImageId = null;

    private ?Uuid $parentId = null;

    /**
     * @Assert\NotBlank
     */
    private Uuid $languageId;

    /**
     * @Assert\NotBlank
     */
    private Uuid $userId;

    /**
     * @Assert\NotNull
     *
     * @var Uuid[] Used for deserialization
     */
    private array $storyThemeIds = [];

    public function __construct(string $title = '', string $content = '', string $extract = '', ?Uuid $storyImageId = null, ?Uuid $parentId = null, Uuid $languageId, Uuid $userId, array $storyThemeIds = [])
    {
        $this->title = $title;
        $this->content = $content;
        $this->extract = $extract;
        $this->storyImageId = $storyImageId;
        $this->parentId = $parentId;
        $this->languageId = $languageId;
        $this->userId = $userId;
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

    public function getStoryImageId(): ?Uuid
    {
        return $this->storyImageId;
    }

    public function getParentId(): ?Uuid
    {
        return $this->parentId;
    }

    public function getLanguageId(): Uuid
    {
        return $this->languageId;
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }

    public function getStoryThemeIds(): array
    {
        return $this->storyThemeIds;
    }
}
