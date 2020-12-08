<?php

declare(strict_types=1);

namespace App\Story\Entity;

use App\Entity\AbstractEntity;
use App\File\ImageableInterface;
use App\File\ImageableTrait;
use App\Language\Entity\TranslatableInterface;
use App\Language\Entity\TranslatableTrait;
use App\Story\Exception\StoryThemeDepthException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="sty_story_image")
 * @ORM\Entity(repositoryClass="App\Story\Repository\StoryImageRepository")
 */
class StoryImage extends AbstractEntity implements ImageableInterface, TranslatableInterface
{
    use ImageableTrait;
    use TranslatableTrait;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\StoryImageTranslation", mappedBy="storyImage", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $storyImageTranslations;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\Story", mappedBy="storyImage")
     */
    private Collection $stories;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\StoryImageHasStoryTheme", mappedBy="storyImage", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $storyImageHasStoryThemes;

    public function __construct(string $reference = '')
    {
        parent::__construct();

        // init zero values
        $this->reference = '';
        $this->storyImageTranslations = new ArrayCollection();
        $this->stories = new ArrayCollection();
        $this->storyImageHasStoryThemes = new ArrayCollection();

        // init values
        $this->setReference($reference);
    }

    public function getImageBasePath(): string
    {
        return 'story';
    }

    public function getStoryImageTranslations(): Collection
    {
        return $this->getTranslations();
    }

    public function addStoryImageTranslation(StoryImageTranslation $storyImageTranslation): self
    {
        $this->storyImageTranslations[] = $storyImageTranslation;

        return $this;
    }

    public function removeStoryImageTranslation(StoryImageTranslation $storyImageTranslation): self
    {
        $this->storyImageTranslations->removeElement($storyImageTranslation);

        return $this;
    }

    public function getTranslations(): Collection
    {
        return $this->getStoryImageTranslations();
    }

    public function getStories(): Collection
    {
        return $this->stories;
    }

    public function addStory(Story $story): self
    {
        $this->stories[] = $story;

        return $this;
    }

    public function removeStory(Story $story): self
    {
        $this->stories->removeElement($story);

        return $this;
    }

    public function getStoryImageHasStoryThemes(): Collection
    {
        return $this->storyImageHasStoryThemes;
    }

    public function addStoryImageHasStoryTheme(StoryImageHasStoryTheme $storyImageHasStoryTheme): self
    {
        $this->storyImageHasStoryThemes[] = $storyImageHasStoryTheme;

        return $this;
    }

    public function removeStoryImageHasStoryTheme(StoryImageHasStoryTheme $storyImageHasStoryTheme): self
    {
        $this->storyImageHasStoryThemes->removeElement($storyImageHasStoryTheme);

        return $this;
    }

    public function addStoryTheme(StoryTheme $storyTheme): self
    {
        if ($storyTheme->getChildren()->count() > 0) {
            throw new StoryThemeDepthException();
        }

        new StoryImageHasStoryTheme($this, $storyTheme);

        return $this;
    }
}
