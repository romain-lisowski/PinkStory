<?php

declare(strict_types=1);

namespace App\Story\Model\Entity;

use App\Language\Model\Entity\AbstractTranslation;
use App\Language\Model\Entity\Language;
use App\Language\Model\LanguageInterface;
use App\Model\EditableInterface;
use App\Model\EditableTrait;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="sty_story_theme_translation")
 * @ORM\Entity(repositoryClass="App\Story\Repository\Entity\StoryThemeTranslationRepository")
 */
class StoryThemeTranslation extends AbstractTranslation implements EditableInterface
{
    use EditableTrait;

    /**
     * @Serializer\Groups({"serializer"})
     * @Assert\NotBlank
     * @ORM\Column(name="title", type="string", length=255)
     */
    private string $title;

    /**
     * @Serializer\Groups({"serializer"})
     * @Assert\NotBlank
     * @ORM\Column(name="title_slug", type="string", length=255)
     */
    private string $titleSlug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Model\Entity\StoryTheme", inversedBy="storyThemeTranslations")
     * @ORM\JoinColumn(name="story_theme_id", referencedColumnName="id", nullable=false)
     */
    private StoryTheme $storyTheme;

    /**
     * @ORM\ManyToOne(targetEntity="App\Language\Model\Entity\Language", inversedBy="storyThemeTranslations")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", nullable=false)
     */
    private LanguageInterface $language;

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

    public function setLanguage(LanguageInterface $language): self
    {
        if (!$language instanceof Language) {
            throw new InvalidArgumentException();
        }

        $this->language = $language;
        $language->addStoryThemeTranslation($this);

        return $this;
    }

    public function updateLanguage(LanguageInterface $language): self
    {
        if ($this->language instanceof Language) {
            $this->language->removeStoryThemeTranslation($this);
        }

        $this->setLanguage($language);

        return $this;
    }
}
