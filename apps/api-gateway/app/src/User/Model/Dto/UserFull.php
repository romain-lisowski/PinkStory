<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\Language\Model\Dto\Language;
use App\User\Model\UserEditableInterface;
use App\User\Model\UserEditableTrait;
use DateTime;
use Symfony\Component\Serializer\Annotation as Serializer;

final class UserFull extends UserMedium implements UserEditableInterface
{
    // Not use dto one, to avoid circular reference with serializer.
    use UserEditableTrait;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $email;

    /**
     * Cause we don't use dto one.
     *
     * @Serializer\Groups({"serializer"})
     */
    private bool $editable = false;

    public function __construct(string $id = '', bool $imageDefined = false, string $name = '', string $nameSlug = '', string $email = '', DateTime $createdAt, Language $language)
    {
        parent::__construct($id, $imageDefined, $name, $nameSlug, $createdAt, $language);

        $this->user = $this;
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
