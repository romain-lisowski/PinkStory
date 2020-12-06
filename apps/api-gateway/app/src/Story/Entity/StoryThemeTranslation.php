<?php

declare(strict_types=1);

namespace App\Story\Entity;

use App\Language\Entity\AbstractTranslation;
use App\Language\Entity\Language;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="sty_story_theme_translation")
 * @ORM\Entity(repositoryClass="App\Story\Repository\StoryThemeTranslationRepository")
 */
class StoryThemeTranslation extends AbstractTranslation
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
     * @ORM\Column(name="title_slug", type="string", length=255)
     */
    private string $titleSlug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Entity\StoryTheme", inversedBy="storyThemeTranslations")
     * @ORM\JoinColumn(name="story_theme_id", referencedColumnName="id", nullable=false)
     */
    private StoryTheme $storyTheme;

    /**
     * @ORM\ManyToOne(targetEntity="App\Language\Entity\Language", inversedBy="storyThemeTranslations")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", nullable=false)
     */
    private Language $language;

    public function __construct(string $title = '', StoryTheme $storyTheme, Language $language)
    {
        parent::__construct($language);

        // init zero values
        $this->title = '';
        $this->titleSlug = '';

        // init values
        $this->setTitle($title)
            ->setStoryTheme($storyTheme)
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

    public function getStoryTheme(): StoryTheme
    {
        return $this->storyTheme;
    }

    public function setStoryTheme(StoryTheme $storyTheme): self
    {
        $this->storyTheme = $storyTheme;
        $storyTheme->addStoryThemeTranslation($this);

        return $this;
    }

    public function setLanguage(Language $language): self
    {
        $this->language = $language;
        $language->addStoryThemeTranslation($this);

        return $this;
    }

    public function updateLanguage(Language $language): self
    {
        $this->language->removeStoryThemeTranslation($this);

        $this->setLanguage($language);

        return $this;
    }
}
