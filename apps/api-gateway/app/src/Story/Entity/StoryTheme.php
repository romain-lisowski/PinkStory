<?php

declare(strict_types=1);

namespace App\Story\Entity;

use App\Entity\AbstractEntity;
use App\Entity\PositionInterface;
use App\Entity\PositionTrait;
use App\Language\Entity\TranslatableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="sty_story_theme")
 * @ORM\Entity(repositoryClass="App\Story\Repository\StoryThemeRepository")
 */
class StoryTheme extends AbstractEntity implements PositionInterface, TranslatableInterface
{
    use PositionTrait;

    /**
     * @Groups({"medium", "full"})
     * @Assert\NotBlank
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private string $reference;

    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Entity\StoryTheme", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private ?StoryTheme $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\StoryTheme", mappedBy="parent", cascade={"remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private Collection $children;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\StoryThemeTranslation", mappedBy="storyTheme", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $storyThemeTranslations;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\StoryHasStoryTheme", mappedBy="storyTheme", cascade={"remove"}, orphanRemoval=true)
     */
    private Collection $storyHasStoryThemes;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\StoryImageHasStoryTheme", mappedBy="storyTheme", cascade={"remove"}, orphanRemoval=true)
     */
    private Collection $storyImageHasStoryThemes;

    public function __construct(string $reference = '', ?StoryTheme $parent = null)
    {
        parent::__construct();

        // init zero values
        $this->reference = '';
        $this->parent = null;
        $this->position = 1;
        $this->children = new ArrayCollection();
        $this->storyThemeTranslations = new ArrayCollection();
        $this->storyHasStoryThemes = new ArrayCollection();
        $this->storyImageHasStoryThemes = new ArrayCollection();

        // init values
        $this->setReference($reference)
            ->setParent($parent)
        ;
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

    public function getParent(): ?StoryTheme
    {
        return $this->parent;
    }

    public function setParent(?StoryTheme $parent): self
    {
        $this->parent = $parent;

        if (null !== $parent) {
            $this->initPosition($this->parent->getChildren());
            $parent->addChild($this);
        } else {
            $this->initPosition(null);
        }

        return $this;
    }

    public function updateParent(?StoryTheme $parent): self
    {
        if (null !== $this->parent) {
            $this->parent->removeChild($this);
        }

        $this->setParent($parent);

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(StoryTheme $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    public function removeChild(StoryTheme $child): self
    {
        $this->children->remove($child);
        self::resetPosition($this->children);

        return $this;
    }

    public function getStoryThemeTranslations(): Collection
    {
        return $this->storyThemeTranslations;
    }

    public function addStoryThemeTranslation(StoryThemeTranslation $storyThemeTranslation): self
    {
        $this->storyThemeTranslations[] = $storyThemeTranslation;

        return $this;
    }

    public function removeStoryThemeTranslation(StoryThemeTranslation $storyThemeTranslation): self
    {
        $this->storyThemeTranslations->remove($storyThemeTranslation);

        return $this;
    }

    public function getTranslations(): Collection
    {
        return $this->getStoryThemeTranslations();
    }

    public function getStoryHasStoryThemes(): Collection
    {
        return $this->storyHasStoryThemes;
    }

    public function addStoryHasStoryTheme(StoryHasStoryTheme $storyHasStoryTheme): self
    {
        $this->storyHasStoryThemes[] = $storyHasStoryTheme;

        return $this;
    }

    public function removeStoryHasStoryTheme(StoryHasStoryTheme $storyHasStoryTheme): self
    {
        $this->storyHasStoryThemes->remove($storyHasStoryTheme);

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
}
