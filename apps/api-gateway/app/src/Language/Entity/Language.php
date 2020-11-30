<?php

declare(strict_types=1);

namespace App\Language\Entity;

use App\Entity\AbstractEntity;
use App\Story\Entity\StoryImageTranslation;
use App\Story\Entity\StoryThemeTranslation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="lng_language")
 * @ORM\Entity(repositoryClass="App\Language\Repository\LanguageRepository")
 */
class Language extends AbstractEntity
{
    /**
     * @Groups({"medium", "full"})
     * @Assert\NotBlank
     * @ORM\Column(name="title", type="string", length=255)
     */
    private string $title;

    /**
     * @Groups({"medium", "full"})
     * @Assert\NotBlank
     * @ORM\Column(name="locale", type="string", length=255)
     */
    private string $locale;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\StoryThemeTranslation", mappedBy="language", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $storyThemeTranslations;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Entity\StoryImageTranslation", mappedBy="language", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $storyImageTranslations;

    public function __construct(string $title = '', string $locale = '')
    {
        parent::__construct();

        // init zero values
        $this->title = '';
        $this->locale = '';
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
        $this->storyImageTranslations->remove($storyImageTranslation);

        return $this;
    }
}
