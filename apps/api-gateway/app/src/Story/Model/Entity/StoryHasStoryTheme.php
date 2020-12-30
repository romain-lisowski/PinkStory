<?php

declare(strict_types=1);

namespace App\Story\Model\Entity;

use App\Model\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="sty_story_has_story_theme", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_STORY_HAS_STORY_THEME", columns={"story_id", "story_theme_id"})})
 * @ORM\Entity(repositoryClass="App\Story\Repository\Entity\StoryHasStoryThemeRepository")
 * @UniqueEntity(
 *      fields = {"story", "storyTheme"}
 * )
 */
class StoryHasStoryTheme extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Model\Entity\Story", inversedBy="storyHasStoryThemes")
     * @ORM\JoinColumn(name="story_id", referencedColumnName="id", nullable=false)
     */
    private Story $story;

    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Model\Entity\StoryTheme", inversedBy="storyHasStoryThemes")
     * @ORM\JoinColumn(name="story_theme_id", referencedColumnName="id", nullable=false)
     */
    private StoryTheme $storyTheme;

    public function __construct(Story $story, StoryTheme $storyTheme)
    {
        parent::__construct();

        // init values
        $this->setStory($story)
            ->setStoryTheme($storyTheme)
        ;
    }

    public function getStory(): Story
    {
        return $this->story;
    }

    public function setStory(Story $story): self
    {
        $this->story = $story;
        $story->addStoryHasStoryTheme($this);

        return $this;
    }

    public function getStoryTheme(): StoryTheme
    {
        return $this->storyTheme;
    }

    public function setStoryTheme(StoryTheme $storyTheme): self
    {
        $this->storyTheme = $storyTheme;
        $storyTheme->addStoryHasStoryTheme($this);

        return $this;
    }
}
