<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

final class UserUpdateImageCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $id;

    /**
     * @Assert\File(
     *      mimeTypes = {"image/jpeg", "image/png"},
     * )
     */
    private File $image;

    public function __construct(string $id, File $image = null)
    {
        $this->id = $id;
        $this->image = $image;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getImage(): File
    {
        return $this->image;
    }
}
