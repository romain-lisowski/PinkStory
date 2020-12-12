<?php

declare(strict_types=1);

namespace App\Language\Model\Entity;

use App\Model\Entity\AbstractEntity;
use App\Model\EditableInterface;
use App\Model\EditableTrait;
use App\File\ImageableInterface;
use App\File\ImageableTrait;
use App\Language\Model\LanguageInterface;
use App\Story\Entity\Story;
use App\Story\Entity\StoryImageTranslation;
use App\Story\Entity\StoryThemeTranslation;
use App\User\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="lng_language")
 * @ORM\Entity(repositoryClass="App\Language\Repository\Entity\LanguageRepository")
 * @UniqueEntity(
 *      fields = {"locale"}
 * )
 */
class Language extends AbstractEntity implements LanguageInterface, ImageableInterface, EditableInterface
{
    use ImageableTrait;
    use EditableTrait;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="title", type="string", length=255)
     */
    private string $title;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="locale", type="string", length=255, unique=true)
     */
    private string $locale;

    /**
     * @ORM\OneToMany(targetEntity="App\User\Entity\User", mappedBy="language")
     */
    private Collection $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\Story", mappedBy="language")
     */
    private Collection $stories;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\StoryThemeTranslation", mappedBy="language")
     */
    private Collection $storyThemeTranslations;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\StoryImageTranslation", mappedBy="language")
     */
    private Collection $storyImageTranslations;

    public function __construct(string $title = '', string $locale = '')
    {
        parent::__construct();

        // init zero values
        $this->title = '';
        $this->locale = '';
        $this->users = new ArrayCollection();
        $this->stories = new ArrayCollection();
        $this->storyThemeTranslations = new ArrayCollection();
        $this->storyImageTranslations = new ArrayCollection();

        // init values
        $this->setTitle($title)
            ->setLocale($locale)
        ;
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

    public function updateTitle(string $title): self
    {
        $this->setTitle($title);

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $slugger = new AsciiSlugger();
        $this->locale = $slugger->slug($locale)->lower()->toString();

        return $this;
    }

    public function updateLocale(string $locale): self
    {
        $this->setLocale($locale);

        return $this;
    }

    public function getImageBasePath(): string
    {
        return 'language';
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        $this->users[] = $user;

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
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

    public function getStoryImageTranslations(): Collection
    {
        return $this->storyImageTranslations;
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
}
