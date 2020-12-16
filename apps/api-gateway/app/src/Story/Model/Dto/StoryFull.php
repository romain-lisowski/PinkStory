<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use App\Language\Model\Dto\Language;
use App\User\Model\Dto\User;
use DateTime;

class StoryFull extends StoryMedium
{
    public function __construct(string $id = '', string $title = '', string $titleSlug = '', string $content = '', DateTime $createdAt, User $user, Language $language)
    {
        parent::__construct($id, $title, $titleSlug, $content, $createdAt, $user, $language);
    }
}
