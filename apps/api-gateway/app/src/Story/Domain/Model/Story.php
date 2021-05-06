<?php

declare(strict_types=1);

namespace App\Story\Domain\Model;

use App\Common\Domain\Model\AbstractEntity;
use App\Common\Domain\Model\ChildDepthException;
use App\Common\Domain\Model\PositionableInterface;
use App\Common\Domain\Model\PositionableTrait;
use App\Language\Domain\Model\Language;
use App\Language\Domain\Model\LanguageableInterface;
use App\Story\Domain\Repository\StoryThemeNoResultException;
use App\Story\Domain\Repository\StoryThemeRepositoryInterface;
use App\User\Domain\Model\User;
use App\User\Domain\Model\UserableInterface;
use App\User\Domain\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Story\Infrastructure\Doctrine\Repository\StoryDoctrineORMRepository")
 * @ORM\Table(name="sty_story")
 */
class Story extends AbstractEntity implements UserableInterface, LanguageableInterface, PositionableInterface
{
    use PositionableTrait;

    /**
     * @ORM\Column(name="title", type="string")
     * @Assert\NotBlank
     */
    private string $title;

    /**
     * @ORM\Column(name="title_slug", type="string")
     * @Assert\NotBlank
     */
    private string $titleSlug;

    /**
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank
     */
    private string $content;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 140
     * )
     * @ORM\Column(name="extract", type="text")
     */
    private string $extract;

    /**
     * @ORM\ManyToOne(targetEntity="App\User\Domain\Model\User", inversedBy="stories")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Language\Domain\Model\Language", inversedBy="stories")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", nullable=false)
     */
    private Language $language;

    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Domain\Model\Story", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private ?Story $parent = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Domain\Model\Story", mappedBy="parent", cascade={"remove"})
     */
    private Collection $children;

    /**
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private ?int $position = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Domain\Model\StoryImage", inversedBy="stories")
     * @ORM\JoinColumn(name="story_image_id", referencedColumnName="id")
     */
    private ?StoryImage $storyImage = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Domain\Model\StoryHasStoryTheme", mappedBy="story", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $storyHasStoryThemes;

    public function __construct()
    {
        parent::__construct();

        // init values
        $this->children = new ArrayCollection();
        $this->storyHasStoryThemes = new ArrayCollection();
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

        return $this;
    }

    public function updateTitle(string $title): self
    {
        $this->setTitle($title);
        $this->updateLastUpdatedAt();

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
        $this->updateLastUpdatedAt();

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

    public function updateExtract(string $extract): self
    {
        $this->setExtract($extract);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        $user->addStory($this);

        return $this;
    }

    public function updateUser(User $user): self
    {
        $this->user->removeStory($this);
        $this->setUser($user);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function setLanguage(Language $language): self
    {
        $this->language = $language;
        $language->addStory($this);

        return $this;
    }

    public function updateLanguage(Language $language): self
    {
        $this->language->removeStory($this);
        $this->setLanguage($language);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getParent(): ?Story
    {
        return $this->parent;
    }

    /**
     * @throws ChildDepthException
     */
    public function setParent(?Story $parent): self
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

    public function updateParent(?Story $parent): self
    {
        if (null !== $this->parent) {
            $this->parent->removeChild($this);
        }

        $this->setParent($parent);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Story $child): self
    {
        $this->children[] = $child;
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function removeChild(Story $child): self
    {
        $this->children->removeElement($child);
        static::resetPositions($this->children);
        $this->updateLastUpdatedAt();

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
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getStoryHasStoryThemes(): Collection
    {
        return $this->storyHasStoryThemes;
    }

    public function addStoryHasStoryTheme(StoryHasStoryTheme $storyHasStoryTheme): self
    {
        $this->storyHasStoryThemes[] = $storyHasStoryTheme;
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function removeStoryHasStoryTheme(StoryHasStoryTheme $storyHasStoryTheme): self
    {
        $this->storyHasStoryThemes->removeElement($storyHasStoryTheme);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getStoryThemes(): Collection
    {
        $storyThemes = array_values(array_map(function (StoryHasStoryTheme $storyHasStoryTheme) {
            return $storyHasStoryTheme->getStoryTheme();
        }, $this->storyHasStoryThemes->toArray()));

        return new ArrayCollection($storyThemes);
    }

    public function addStoryTheme(StoryTheme $storyTheme): self
    {
        $exists = false;

        foreach ($this->getStoryHasStoryThemes() as $storyHasStoryTheme) {
            if ($storyHasStoryTheme->getStoryTheme()->getId() === $storyTheme->getId()) {
                $exists = true;

                break;
            }
        }

        if (false === $exists) {
            (new StoryHasStoryTheme())
                ->setStory($this)
                ->setStoryTheme($storyTheme)
            ;
        }

        return $this;
    }

    /**
     * @throws StoryThemeNoResultException
     */
    public function addStoryThemes(array $storyThemeIds, StoryThemeRepositoryInterface $storyThemeRepository): self
    {
        foreach ($storyThemeIds as $storyThemeId) {
            if (false === Uuid::isValid($storyThemeId)) {
                throw new StoryThemeNoResultException();
            }

            $storyTheme = $storyThemeRepository->findOne($storyThemeId);
            $this->addStoryTheme($storyTheme);
        }

        return $this;
    }

    public function cleanStoryThemes(array $storyThemeIds): self
    {
        foreach ($this->storyHasStoryThemes as $storyHasStoryTheme) {
            if (false === in_array($storyHasStoryTheme->getStoryTheme()->getId(), $storyThemeIds)) {
                $this->removeStoryHasStoryTheme($storyHasStoryTheme);
            }
        }

        return $this;
    }

    public function updateStoryThemes(array $storyThemeIds, StoryThemeRepositoryInterface $storyThemeRepository): self
    {
        $this->addStoryThemes($storyThemeIds, $storyThemeRepository);
        $this->cleanStoryThemes($storyThemeIds);

        return $this;
    }
}
