<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\User\Model\UserEditableInterface;
use App\User\Model\UserInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

final class UserFull extends User implements UserEditableInterface
{
    use UserEditableTrait;

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
    private string $email;

    private bool $imageDefined;

    public function __construct(string $id = '', string $name = '', string $nameSlug = '', string $email = '', bool $imageDefined = false)
    {
        parent::__construct($id);

        $this->name = $name;
        $this->nameSlug = $nameSlug;
        $this->email = $email;
        $this->imageDefined = $imageDefined;
        $this->user = $this;
    }

    public function setUser(UserInterface $user): self
    {
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNameSlug(): string
    {
        return $this->nameSlug;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function hasImage(): bool
    {
        return $this->imageDefined;
    }
}
