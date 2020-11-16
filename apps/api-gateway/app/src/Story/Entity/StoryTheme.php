<?php

declare(strict_types=1);

namespace App\Story\Entity;

use App\Entity\AbstractEntity;
use App\Entity\PositionInterface;
use App\Entity\PositionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="sty_story_theme")
 * @ORM\Entity(repositoryClass="App\Story\Repository\StoryThemeRepository")
 */
class StoryTheme extends AbstractEntity implements PositionInterface
{
    use PositionTrait;

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
     * @ORM\OneToMany(targetEntity="App\Story\Entity\StoryHasStoryTheme", mappedBy="storyTheme", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $storyHasStoryThemes;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\StoryImageHasStoryTheme", mappedBy="storyTheme", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $storyImageHasStoryThemes;

    public function __construct(string $title = '', ?StoryTheme $parent = null)
    {
        parent::__construct();

        // init zero values
        $this->title = '';
        $this->titleSlug = '';
        $this->parent = null;
        $this->position = 1;
        $this->children = new ArrayCollection();
        $this->storyHasStoryThemes = new ArrayCollection();
        $this->storyImageHasStoryThemes = new ArrayCollection();

        // init values
        $this->setTitle($title)
            ->setParent($parent)
        ;
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
