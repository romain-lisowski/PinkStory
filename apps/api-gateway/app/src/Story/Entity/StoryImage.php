<?php

declare(strict_types=1);

namespace App\Story\Entity;

use App\Entity\AbstractEntity;
use App\File\ImageInterface;
use App\File\ImageTrait;
use App\Language\Entity\TranslatableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="sty_story_image")
 * @ORM\Entity(repositoryClass="App\Story\Repository\StoryImageRepository")
 */
class StoryImage extends AbstractEntity implements ImageInterface, TranslatableInterface
{
    use ImageTrait;

    /**
     * @Groups({"medium", "full"})
     * @Assert\NotBlank
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private string $reference;

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

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function updateReference(string $reference): self
    {
        $this->setReference($reference);

        return $this;
    }

    public function hasImage(): bool
    {
        return true;
    }

    public function getImageBasePath(): string
    {
        return '/story';
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
        $this->storyImageTranslations->remove($storyImageTranslation);

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
