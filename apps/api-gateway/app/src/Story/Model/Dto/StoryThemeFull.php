<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use Symfony\Component\Serializer\Annotation as Serializer;

class StoryThemeFull extends StoryTheme
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $title;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $titleSlug;

    public function __construct(string $id = '', string $title = '', string $titleSlug = '')
    {
        parent::__construct($id);

        $this->title = $title;
        $this->titleSlug = $titleSlug;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTitleSlug(): string
    {
        return $this->titleSlug;
    }
}
