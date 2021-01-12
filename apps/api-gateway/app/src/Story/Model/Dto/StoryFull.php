<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use App\Language\Model\Dto\Language;
use App\User\Model\Dto\User;
use DateTime;
use Symfony\Component\Serializer\Annotation as Serializer;

class StoryFull extends StoryMedium
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $content;

    public function __construct(string $id = '', string $title = '', string $titleSlug = '', string $content = '', string $extract = '', DateTime $createdAt, User $user, Language $language)
    {
        parent::__construct($id, $title, $titleSlug, $extract, $createdAt, $user, $language);

        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
