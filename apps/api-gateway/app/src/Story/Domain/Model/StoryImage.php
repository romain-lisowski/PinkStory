<?php

declare(strict_types=1);

namespace App\Story\Domain\Model;

use App\Common\Domain\File\ImageableInterface;
use App\Common\Domain\File\ImageableTrait;
use App\Common\Domain\Model\AbstractEntity;
use App\Language\Domain\Model\TranslatableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Story\Infrastructure\Doctrine\Repository\StoryImageDoctrineORMRepository")
 * @ORM\Table(name="sty_story_image")
 */
class StoryImage extends AbstractEntity implements ImageableInterface, TranslatableInterface
{
    use ImageableTrait;

    /**
     * @ORM\Column(name="reference", type="string")
     * @Assert\NotBlank
     */
    private string $reference;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Domain\Model\StoryImageTranslation", mappedBy="storyImage", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $storyImageTranslations;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Domain\Model\StoryImageHasStoryTheme", mappedBy="storyImage", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $storyImageHasStoryThemes;

    public function __construct()
    {
        parent::__construct();

        // init values
        $this->storyImageTranslations = new ArrayCollection();
        $this->storyImageHasStoryThemes = new ArrayCollection();
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
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getStoryImageTranslations(): Collection
    {
        return $this->storyImageTranslations;
    }

    public function addStoryImageTranslation(StoryImageTranslation $storyImageTranslation): self
    {
        $this->storyImageTranslations[] = $storyImageTranslation;
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function removeStoryImageTranslation(StoryImageTranslation $storyImageTranslation): self
    {
        $this->storyImageTranslations->removeElement($storyImageTranslation);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getTranslations(): Collection
    {
        return $this->getStoryImageTranslations();
    }

    public function getStoryImageHasStoryThemes(): Collection
    {
        return $this->storyImageHasStoryThemes;
    }

    public function addStoryImageHasStoryTheme(StoryImageHasStoryTheme $storyImageHasStoryTheme): self
    {
        $this->storyImageHasStoryThemes[] = $storyImageHasStoryTheme;
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function removeStoryImageHasStoryTheme(StoryImageHasStoryTheme $storyImageHasStoryTheme): self
    {
        $this->storyImageHasStoryThemes->removeElement($storyImageHasStoryTheme);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getImageBasePath(): string
    {
        return 'story';
    }
}
