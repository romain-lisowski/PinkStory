<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use App\Language\Model\Dto\Language;
use App\Language\Model\Dto\LanguageableTrait;
use App\Language\Model\LanguageableInterface;
use App\User\Model\Dto\User;
use App\User\Model\Dto\UserEditableTrait;
use App\User\Model\UserEditableInterface;
use DateTime;
use Symfony\Component\Serializer\Annotation as Serializer;

final class StoryFull extends Story implements UserEditableInterface, LanguageableInterface
{
    use UserEditableTrait;
    use LanguageableTrait;

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
    private string $content;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private DateTime $createdAt;

    public function __construct(string $id = '', string $title = '', string $titleSlug = '', string $content = '', DateTime $createdAt, User $user, Language $language)
    {
        parent::__construct($id);

        $this->title = $title;
        $this->titleSlug = $titleSlug;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->language = $language;
        $this->user = $user;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTitleSlug(): string
    {
        return $this->titleSlug;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
