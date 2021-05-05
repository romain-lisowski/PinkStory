<?php

declare(strict_types=1);

namespace App\Language\Domain\Model;

use App\Common\Domain\File\ImageableInterface;
use App\Common\Domain\File\ImageableTrait;
use App\Common\Domain\Model\AbstractEntity;
use App\Story\Domain\Model\StoryImageTranslation;
use App\Story\Domain\Model\StoryThemeTranslation;
use App\User\Domain\Model\User;
use App\User\Domain\Model\UserHasReadingLanguage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Language\Infrastructure\Doctrine\Repository\LanguageDoctrineORMRepository")
 * @ORM\Table(name="lng_language")
 * @UniqueEntity(
 *      fields = {"locale"}
 * )
 */
class Language extends AbstractEntity implements ImageableInterface
{
    use ImageableTrait;

    /**
     * @ORM\Column(name="title", type="string")
     * @Assert\NotBlank
     */
    private string $title;

    /**
     * @ORM\Column(name="locale", type="string", unique=true)
     * @Assert\NotBlank
     */
    private string $locale;

    /**
     * @ORM\OneToMany(targetEntity="App\User\Domain\Model\User", mappedBy="language")
     */
    private Collection $users;

    /**
     * @ORM\OneToMany(targetEntity="App\User\Domain\Model\UserHasReadingLanguage", mappedBy="language")
     */
    private Collection $userHasReadingLanguages;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Domain\Model\StoryThemeTranslation", mappedBy="language")
     */
    private Collection $storyThemeTranslations;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Domain\Model\StoryImageTranslation", mappedBy="language")
     */
    private Collection $storyImageTranslations;

    public function __construct()
    {
        parent::__construct();

        // init values
        $this->users = new ArrayCollection();
        $this->userHasReadingLanguages = new ArrayCollection();
        $this->storyThemeTranslations = new ArrayCollection();
        $this->storyImageTranslations = new ArrayCollection();
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
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function updateLocale(string $locale): self
    {
        $this->setLocale($locale);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        $this->users[] = $user;
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getUserHasReadingLanguages(): Collection
    {
        return $this->userHasReadingLanguages;
    }

    public function addUserHasReadingLanguage(UserHasReadingLanguage $userHasReadingLanguage): self
    {
        $this->userHasReadingLanguages[] = $userHasReadingLanguage;
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function removeUserHasReadingLanguage(UserHasReadingLanguage $userHasReadingLanguage): self
    {
        $this->userHasReadingLanguages->removeElement($userHasReadingLanguage);
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
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function removeStoryThemeTranslation(StoryThemeTranslation $storyThemeTranslation): self
    {
        $this->storyThemeTranslations->removeElement($storyThemeTranslation);
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

    public function getImageBasePath(): string
    {
        return 'language';
    }
}
