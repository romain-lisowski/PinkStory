<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\CommandInterface;
use App\Form\FormableInterface;
use App\Form\FormableTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final class UserUpdateImageCommand implements CommandInterface, FormableInterface
{
    use FormableTrait;

    /**
     * @Assert\NotBlank
     */
    public string $id;

    /**
     * @Assert\NotNull
     * @Assert\File(
     *      mimeTypes = {"image/jpeg", "image/png"},
     * )
     */
    public UploadedFile $image;
}
