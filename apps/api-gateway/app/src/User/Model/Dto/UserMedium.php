<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\Language\Model\Dto\Language;
use App\Language\Model\Dto\LanguageableTrait;
use App\Language\Model\LanguageableInterface;
use App\User\Model\UserEditableInterface;
use App\User\Model\UserEditableTrait;
use DateTime;
use Symfony\Component\Serializer\Annotation as Serializer;

class UserMedium extends User implements UserEditableInterface, LanguageableInterface
{
    // Not use dto one, to avoid circular reference with serializer.
    use UserEditableTrait;
    use LanguageableTrait;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $name;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $nameSlug;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private DateTime $createdAt;

    /**
     * Cause we don't use dto one.
     *
     * @Serializer\Groups({"serializer"})
     */
    private bool $editable = false;

    public function __construct(string $id = '', bool $imageDefined = false, string $name = '', string $nameSlug = '', DateTime $createdAt, Language $language)
    {
        parent::__construct($id, $imageDefined);

        $this->user = $this;
        $this->name = $name;
        $this->nameSlug = $nameSlug;
        $this->createdAt = $createdAt;
        $this->language = $language;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNameSlug(): string
    {
        return $this->nameSlug;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
