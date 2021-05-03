<?php

declare(strict_types=1);

namespace App\Story\Domain\Model;

use App\Common\Domain\Model\AbstractEntity;
use App\Common\Domain\Model\ChildDepthException;
use App\Common\Domain\Model\PositionableInterface;
use App\Common\Domain\Model\PositionableTrait;
use App\Language\Domain\Model\TranslatableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Story\Infrastructure\Doctrine\Repository\StoryThemeDoctrineORMRepository")
 * @ORM\Table(name="sty_story_theme")
 */
class StoryTheme extends AbstractEntity implements TranslatableInterface, PositionableInterface
{
    use PositionableTrait;

    /**
     * @ORM\Column(name="reference", type="string")
     * @Assert\NotBlank
     */
    private string $reference;

    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Domain\Model\StoryTheme", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private ?StoryTheme $parent = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Domain\Model\StoryTheme", mappedBy="parent", cascade={"remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private Collection $children;

    /**
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private ?int $position = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Domain\Model\StoryThemeTranslation", mappedBy="storyTheme", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $storyThemeTranslations;

    public function __construct()
    {
        parent::__construct();

        // init values
        $this->children = new ArrayCollection();
        $this->storyThemeTranslations = new ArrayCollection();
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

    public function getParent(): ?StoryTheme
    {
        return $this->parent;
    }

    /**
     * @throws ChildDepthException
     */
    public function setParent(?StoryTheme $parent): self
    {
        $this->parent = $parent;
        $this->position = null;

        if (null !== $parent) {
            if (null !== $parent->getParent()) {
                throw new ChildDepthException();
            }

            static::initPosition($this, $this->parent->getChildren());

            $parent->addChild($this);
        }

        return $this;
    }

    public function updateParent(?StoryTheme $parent): self
    {
        $this->parent->removeChild($this);
        $this->setParent($parent);
        $this->updateLastUpdatedAt();

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
        $this->children->removeElement($child);

        static::resetPositions($this->children);

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function updatePosition(?int $position): self
    {
        $this->setPosition($position);
        $this->updateLastUpdatedAt();

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
        $this->storyThemeTranslations->removeElement($storyThemeTranslation);

        return $this;
    }

    public function getTranslations(): Collection
    {
        return $this->getStoryThemeTranslations();
    }
}
