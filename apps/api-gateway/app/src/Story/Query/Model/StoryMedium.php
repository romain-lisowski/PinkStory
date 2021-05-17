<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

use App\Language\Query\Model\Language;
use App\User\Domain\Model\UserableInterface;
use App\User\Domain\Model\UserInterface;
use App\User\Query\Model\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation as Serializer;

class StoryMedium extends Story implements UserableInterface
{
    private string $title;
    private string $titleSlug;
    private string $extract;
    private int $storyRatingsTotal;
    private ?float $rate;
    private \DateTime $createdAt;
    private User $user;
    private Language $language;
    private ?StoryImage $storyImage;
    private Collection $storyThemes;

    /**
     * @Serializer\Ignore()
     */
    private array $rates;

    public function __construct()
    {
        // init values
        $this->rates = [];
        $this->storyRatingsTotal = 0;
        $this->rate = null;
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

    public function getTitleSlug(): string
    {
        return $this->titleSlug;
    }

    public function setTitleSlug(string $titleSlug): self
    {
        $this->titleSlug = $titleSlug;

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

    public function getStoryRatingsTotal(): int
    {
        return $this->storyRatingsTotal;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function addRate(int $rate): self
    {
        $this->rates[] = $rate;
        $this->storyRatingsTotal = count($this->rates);
        $this->rate = round(array_sum($this->rates) / $this->storyRatingsTotal, 1);

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

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
