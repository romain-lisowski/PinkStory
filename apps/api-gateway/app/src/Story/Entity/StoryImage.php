<?php

declare(strict_types=1);

namespace App\Story\Entity;

use App\Entity\AbstractEntity;
use App\File\ImageInterface;
use App\File\ImageTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="sty_story_image")
 * @ORM\Entity(repositoryClass="App\Story\Repository\StoryImageRepository")
 */
class StoryImage extends AbstractEntity implements ImageInterface
{
    use ImageTrait;

    /**
     * @Groups({"medium", "full"})
     * @Assert\NotBlank
     * @ORM\Column(name="title", type="string", length=255)
     */
    private string $title;

    /**
     * @Groups({"medium", "full"})
     * @Assert\NotBlank
     * @ORM\Column(name="title_slug", type="string", length=255)
     */
    private string $titleSlug;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\Story", mappedBy="storyImage")
     */
    private Collection $stories;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\StoryImageHasStoryTheme", mappedBy="storyImage", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $storyImageHasStoryThemes;

    public function __construct(string $title = '')
    {
        parent::__construct();

        // init zero values
        $this->title = '';
        $this->titleSlug = '';
        $this->stories = new ArrayCollection();
        $this->storyImageHasStoryThemes = new ArrayCollection();

        // init values
        $this->setTitle($title);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        $slugger = new AsciiSlugger();
        $this->titleSlug = $slugger->slug($title)->lower()->toString();

        return $this;
    }

    public function updateTitle(string $title): self
    {
        $this->setTitle($title);

        return $this;
    }

    public function getTitleSlug(): string
    {
        return $this->titleSlug;
    }

    public function hasImage(): bool
    {
        return true;
    }

    public function getImageBasePath(): string
    {
        return '/story';
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
        $this->stories->remove($story);

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
        $this->storyImageHasStoryThemes->remove($storyImageHasStoryTheme);

        return $this;
    }

    public function addStoryTheme(StoryTheme $storyTheme): self
    {
        // todo, only allow child themes here

        new StoryImageHasStoryTheme($this, $storyTheme);

        return $this;
    }
}
