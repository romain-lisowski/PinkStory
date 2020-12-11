<?php

declare(strict_types=1);

namespace App\Story\Dto;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation as Serializer;

final class StoryImageMedium extends StoryImage
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    public string $title;

    public string $titleSlug;

    /**
     * @Serializer\Groups({"serializer"})
     */
    public string $imageUrl;

    /**
     * @Serializer\Groups({"serializer"})
     */
    public Collection $storyThemes;

    public function __construct(string $id = '')
    {
        parent::__construct($id);

        $this->title = '';
        $this->titleSlug = '';
        $this->imageUrl = '';
        $this->storyThemes = new ArrayCollection();
    }
}
