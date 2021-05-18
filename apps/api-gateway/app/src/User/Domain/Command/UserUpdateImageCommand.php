<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use App\Common\Infrastructure\Validator\Constraint as AppAssert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

final class UserUpdateImageCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     * @AppAssert\Entity(
     *      entityClass = "App\User\Domain\Model\User",
     *      message = "user.validator.constraint.user_not_found"
     * )
     */
    private string $id;

    /**
     * @Assert\NotNull
     * @Assert\File(
     *      mimeTypes = {"image/jpeg", "image/png"},
     * )
     */
    private File $image;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getImage(): File
    {
        return $this->image;
    }

    public function setImage(File $image): self
    {
        $this->image = $image;

        return $this;
    }
}
