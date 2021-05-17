<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

use App\Common\Domain\File\ImageableInterface;
use App\Common\Domain\File\ImageableTrait;
use App\Story\Domain\Model\StoryImage as DomainStoryImage;
use Symfony\Component\Serializer\Annotation as Serializer;

class StoryImageMedium extends StoryImage implements ImageableInterface
{
    use ImageableTrait;

    private string $title;
    private string $titleSlug;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitleSlug(): string
    {
        return $this->titleSlug;
    }

    public function setTitleSlug(string $titleSlug): self
    {
        $this->titleSlug = $titleSlug;

        return $this;
    }

    /**
     * @Serializer\Ignore()
     */
    public static function getImageBasePath(): string
    {
        return DomainStoryImage::getImageBasePath();
    }
}
