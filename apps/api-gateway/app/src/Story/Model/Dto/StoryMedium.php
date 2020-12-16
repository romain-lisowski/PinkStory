<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use App\Language\Model\Dto\Language;
use App\Language\Model\Dto\LanguageableTrait;
use App\Language\Model\LanguageableInterface;
use App\User\Model\Dto\User;
use App\User\Model\Dto\UserEditableTrait;
use App\User\Model\UserEditableInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation as Serializer;

class StoryMedium extends Story implements UserEditableInterface, LanguageableInterface
{
    use UserEditableTrait;
    use LanguageableTrait;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $title;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $titleSlug;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $content;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private DateTime $createdAt;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private ?StoryImage $storyImage;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private Collection $storyThemes;

    public function __construct(string $id = '', string $title = '', string $titleSlug = '', string $content = '', DateTime $createdAt, User $user, Language $language)
    {
        parent::__construct($id);

        $this->title = $title;
        $this->titleSlug = $titleSlug;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->user = $user;
        $this->language = $language;
        $this->storyImage = null;
        $this->storyThemes = new ArrayCollection();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTitleSlug(): string
    {
        return $this->titleSlug;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getStoryImage(): ?StoryImage
    {
        return $this->storyImage;
    }

    public function setStoryImage(?StoryImage $storyImage): self
    {
        $this->storyImage = $storyImage;

        return $this;
    }

    public function getStoryThemes(): Collection
    {
        return $this->storyThemes;
    }

    public function addStoryTheme(StoryTheme $storyTheme): self
    {
        $this->storyThemes[] = $storyTheme;

        return $this;
    }
}
