<?php

declare(strict_types=1);

namespace App\Story\Domain\Model;

use App\Common\Domain\Model\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Story\Infrastructure\Doctrine\Repository\StoryHasStoryThemeDoctrineORMRepository")
 * @ORM\Table(name="sty_story_has_story_theme", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_STORY_HAS_STORY_THEME", columns={"story_id", "story_theme_id"})})
 * @UniqueEntity(
 *      fields = {"story", "storyTheme"}
 * )
 */
class StoryHasStoryTheme extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Domain\Model\Story", inversedBy="storyHasStoryThemes")
     * @ORM\JoinColumn(name="story_id", referencedColumnName="id", nullable=false)
     */
    private Story $story;

    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Domain\Model\StoryTheme", inversedBy="storyHasStoryThemes")
     * @ORM\JoinColumn(name="story_theme_id", referencedColumnName="id", nullable=false)
     */
    private StoryTheme $storyTheme;

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
        // only second level can have stories
        if (null === $storyTheme->getParent()) {
            throw new StoryThemeDepthException();
        }

        $this->storyTheme = $storyTheme;
        $storyTheme->addStoryHasStoryTheme($this);

        return $this;
    }
}
