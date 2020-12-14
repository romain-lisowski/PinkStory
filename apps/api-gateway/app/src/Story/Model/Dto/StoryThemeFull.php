<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use App\Model\DepthableInterface;
use App\Model\DepthableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation as Serializer;

final class StoryThemeFull extends StoryTheme implements DepthableInterface
{
    use DepthableTrait;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $title;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $titleSlug;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private Collection $children;

    public function __construct(string $id = '', string $title = '', string $titleSlug = '')
    {
        parent::__construct($id);

        $this->title = $title;
        $this->titleSlug = $titleSlug;
        $this->children = new ArrayCollection();
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
