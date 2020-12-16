<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use App\Language\Model\Dto\Language;
use App\User\Model\Dto\User;
use DateTime;
use Symfony\Component\Serializer\Annotation as Serializer;

final class StoryFullChild extends StoryFull
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    private StoryMedium $parent;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private ?StoryMedium $previous;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private ?StoryMedium $next;

    public function __construct(string $id = '', string $title = '', string $titleSlug = '', string $content = '', DateTime $createdAt, User $user, Language $language, StoryMedium $parent, ?StoryMedium $previous = null, ?StoryMedium $next = null)
    {
        parent::__construct($id, $title, $titleSlug, $content, $createdAt, $user, $language);

        $this->parent = $parent;
        $this->previous = $previous;
        $this->next = $next;
    }

    public function getParent(): StoryMedium
    {
        return $this->parent;
    }

    public function getPrevious(): StoryMedium
    {
        return $this->previous;
    }

    public function getNext(): StoryMedium
    {
        return $this->next;
    }
}
