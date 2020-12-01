<?php

declare(strict_types=1);

namespace App\Story\Entity;

use App\Entity\AbstractEntity;
use App\Entity\PositionableInterface;
use App\Entity\PositionableTrait;
use App\Exception\ChildDepthException;
use App\Language\Entity\Language;
use App\Language\Entity\LanguageableInterface;
use App\Language\Entity\LanguageableTrait;
use App\Story\Exception\StoryThemeDepthException;
use App\User\Entity\User;
use App\User\Entity\UserableInterface;
use App\User\Entity\UserableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="sty_story")
 * @ORM\Entity(repositoryClass="App\Story\Repository\StoryRepository")
 */
class Story extends AbstractEntity implements UserableInterface, LanguageableInterface, PositionableInterface
{
    use UserableTrait;
    use LanguageableTrait;
    use PositionableTrait;

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
     * @Groups({"medium", "full"})
     * @Assert\NotBlank
     * @ORM\Column(name="content", type="text")
     */
    private string $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\User\Entity\User", inversedBy="stories")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Language\Entity\Language", inversedBy="stories")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", nullable=false)
     */
    private Language $language;

    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Entity\Story", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private ?Story $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\Story", mappedBy="parent", cascade={"remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private Collection $children;

    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Entity\StoryImage", inversedBy="stories")
     * @ORM\JoinColumn(name="story_image_id", referencedColumnName="id")
     */
    private ?StoryImage $storyImage;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\StoryHasStoryTheme", mappedBy="story", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $storyHasStoryThemes;

    public function __construct(string $title = '', string $content = '', User $user, Language $language, ?Story $parent = null, ?StoryImage $storyImage = null)
    {
        parent::__construct();

        // init zero values
        $this->title = '';
        $this->titleSlug = '';
        $this->content = '';
        $this->parent = null;
        $this->position = 1;
        $this->children = new ArrayCollection();
        $this->storyImage = null;
        $this->storyHasStoryThemes = new ArrayCollection();

        // init values
        $this->setTitle($title)
            ->setContent($content)
            ->setUser($user)
            ->setLanguage($language)
            ->setParent($parent)
            ->setStoryImage($storyImage)
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

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function updateContent(string $content): self
    {
        $this->setContent($content);

        return $this;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        $user->addStory($this);

        return $this;
    }

    public function setLanguage(Language $language): self
    {
        $this->language = $language;
        $language->addStory($this);

        return $this;
    }

    public function getParent(): ?Story
    {
        return $this->parent;
    }

    public function setParent(?Story $parent): self
    {
        $this->parent = $parent;

        if (null !== $parent) {
            if (null !== $parent->getParent()) {
                throw new ChildDepthException();
            }

            $this->initPosition($this->parent->getChildren());
            $parent->addChild($this);
        } else {
            $this->initPosition(null);
        }

        return $this;
    }

    public function updateParent(?Story $parent): self
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

    public function addChild(Story $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    public function removeChild(Story $child): self
    {
        $this->children->remove($child);
        self::resetPosition($this->children);

        return $this;
    }

    public function getStoryImage(): ?StoryImage
    {
        return $this->storyImage;
    }

    public function setStoryImage(?StoryImage $storyImage): self
    {
        $this->storyImage = $storyImage;

        if (null !== $storyImage) {
            $storyImage->addStory($this);
        }

        return $this;
    }

    public function updateStoryImage(?StoryImage $storyImage): self
    {
        if (null !== $this->storyImage) {
            $this->storyImage->removeStory($this);
        }

        $this->setStoryImage($storyImage);

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

    public function addStoryTheme(StoryTheme $storyTheme): self
    {
        if ($storyTheme->getChildren()->count() > 0) {
            throw new StoryThemeDepthException();
        }

        new StoryHasStoryTheme($this, $storyTheme);

        return $this;
    }
}
