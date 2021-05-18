<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Model\EditableTrait;
use App\Language\Query\Model\Language;
use App\User\Domain\Model\UserableInterface;
use App\User\Domain\Model\UserInterface;
use App\User\Query\Model\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class StoryUpdate extends Story implements UserableInterface, EditableInterface
{
    use EditableTrait;

    private string $title;
    private string $extract;
    private string $content;
    private User $user;
    private Language $language;
    private ?StoryImage $storyImage;
    private Collection $storyThemes;

    public function __construct()
    {
        // init values
        $this->storyImage = null;
        $this->storyThemes = new ArrayCollection();
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

    public function getExtract(): string
    {
        return $this->extract;
    }

    public function setExtract(string $extract): self
    {
        $this->extract = $extract;

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

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function setLanguage(Language $language): self
    {
        $this->language = $language;

        return $this;
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
