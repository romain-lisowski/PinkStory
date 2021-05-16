<?php

declare(strict_types=1);

namespace App\Story\Domain\Model;

use App\Common\Domain\Model\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Story\Infrastructure\Doctrine\Repository\StoryImageHasStoryThemeDoctrineORMRepository")
 * @ORM\Table(name="sty_story_image_has_story_theme", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_STORY_IMAGE_HAS_STORY_THEME", columns={"story_image_id", "story_theme_id"})})
 * @UniqueEntity(
 *      fields = {"storyImage", "storyTheme"}
 * )
 */
class StoryImageHasStoryTheme extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Domain\Model\StoryImage", inversedBy="storyImageHasStoryThemes")
     * @ORM\JoinColumn(name="story_image_id", referencedColumnName="id", nullable=false)
     */
    private StoryImage $storyImage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Domain\Model\StoryTheme", inversedBy="storyImageHasStoryThemes")
     * @ORM\JoinColumn(name="story_theme_id", referencedColumnName="id", nullable=false)
     */
    private StoryTheme $storyTheme;

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
        // only second level can have story images
        if (null === $storyTheme->getParent()) {
            throw new StoryThemeDepthException();
        }

        $this->storyTheme = $storyTheme;
        $storyTheme->addStoryImageHasStoryTheme($this);

        return $this;
    }
}
