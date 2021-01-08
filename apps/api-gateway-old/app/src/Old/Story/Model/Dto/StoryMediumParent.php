<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use App\Language\Model\Dto\Language;
use App\User\Model\Dto\User;
use DateTime;
use Symfony\Component\Serializer\Annotation as Serializer;

class StoryMediumParent extends StoryMedium
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    private int $childrenTotal;

    public function __construct(string $id = '', string $title = '', string $titleSlug = '', string $extract = '', DateTime $createdAt, User $user, Language $language)
    {
        parent::__construct($id, $title, $titleSlug, $extract, $createdAt, $user, $language);

        $this->childrenTotal = 0;
    }

    public function getChildrenTotal(): int
    {
        return $this->childrenTotal;
    }

    public function setChildrenTotal(int $childrenTotal): self
    {
        $this->childrenTotal = $childrenTotal;

        return $this;
    }
}
