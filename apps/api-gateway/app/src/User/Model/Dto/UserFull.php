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

    public function __construct(string $id = '', bool $imageDefined = false, string $name = '', string $nameSlug = '', string $email = '')
    {
        parent::__construct($id, $imageDefined);

        $this->name = $name;
        $this->nameSlug = $nameSlug;
        $this->email = $email;
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
}
