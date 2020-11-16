<?php

declare(strict_types=1);

namespace App\Story\Entity;

use App\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="sty_story_image_has_story_theme")
 * @ORM\Entity(repositoryClass="App\Story\Repository\StoryImageHasStoryThemeRepository")
 */
class StoryImageHasStoryTheme extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Entity\StoryImage", inversedBy="storyImageHasStoryThemes")
     * @ORM\JoinColumn(name="story_image_id", referencedColumnName="id", nullable=false)
     */
    private StoryImage $storyImage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Entity\StoryTheme", inversedBy="storyImageHasStoryThemes")
     * @ORM\JoinColumn(name="story_theme_id", referencedColumnName="id", nullable=false)
     */
    private StoryTheme $storyTheme;

    public function __construct(StoryImage $storyImage, StoryTheme $storyTheme)
    {
        parent::__construct();

        // init values
        $this->setStoryImage($storyImage)
            ->setStoryTheme($storyTheme)
        ;
    }

    public function getStoryImage(): StoryImage
    {
        return $this->storyImage;
    }

    public function setStoryImage(StoryImage $storyImage): self
    {
        $this->storyImage = $storyImage;
        $storyImage->addStoryImageHasStoryTheme($this);

        return $this;
    }

    public function getStoryTheme(): StoryTheme
    {
        return $this->storyTheme;
    }

    public function setStoryTheme(StoryTheme $storyTheme): self
    {
        $this->storyTheme = $storyTheme;
        $storyTheme->addStoryImageHasStoryTheme($this);

        return $this;
    }
}
