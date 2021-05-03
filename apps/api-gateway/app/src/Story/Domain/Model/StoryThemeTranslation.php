<?php

declare(strict_types=1);

namespace App\Story\Domain\Model;

use App\Common\Domain\Model\AbstractEntity;
use App\Language\Domain\Model\Language;
use App\Language\Domain\Model\LanguageableInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Story\Infrastructure\Doctrine\Repository\StoryThemeTranslationDoctrineORMRepository")
 * @ORM\Table(name="sty_story_theme_translation", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_STORY_THEME_TRANSLATION", columns={"story_theme_id", "language_id"})})
 * @UniqueEntity(
 *      fields = {"storyTheme", "language"}
 * )
 */
class StoryThemeTranslation extends AbstractEntity implements LanguageableInterface
{
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
     * @ORM\ManyToOne(targetEntity="App\Story\Domain\Model\StoryTheme", inversedBy="storyThemeTranslations")
     * @ORM\JoinColumn(name="story_theme_id", referencedColumnName="id", nullable=false)
     */
    private StoryTheme $storyTheme;

    /**
     * @ORM\ManyToOne(targetEntity="App\Language\Domain\Model\Language", inversedBy="storyThemeTranslations")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", nullable=false)
     */
    private Language $language;

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
        $this->updateLastUpdatedAt();

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

    public function getLanguage(): Language
    {
        return $this->language;
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
        $this->updateLastUpdatedAt();

        return $this;
    }
}
