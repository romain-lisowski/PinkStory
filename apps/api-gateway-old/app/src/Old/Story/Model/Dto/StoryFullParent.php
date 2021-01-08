<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use App\Language\Model\Dto\Language;
use App\User\Model\Dto\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation as Serializer;

final class StoryFullParent extends StoryFull
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    private Collection $children;

    public function __construct(string $id = '', string $title = '', string $titleSlug = '', string $content = '', string $extract = '', DateTime $createdAt, User $user, Language $language)
    {
        parent::__construct($id, $title, $titleSlug, $content, $extract, $createdAt, $user, $language);

        $this->children = new ArrayCollection();
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function setChildren(Collection $children): self
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @Serializer\Groups({"serializer"})
     */
    public function getChildrenTotal(): int
    {
        return count($this->children);
    }
}
