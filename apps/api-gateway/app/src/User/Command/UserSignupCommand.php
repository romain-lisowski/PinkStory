<?php

declare(strict_types=1);

namespace App\User\Command;

use App\User\Validator\Constraints as AppUserAssert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final class UserSignupCommand
{
    /**
     * @Assert\NotBlank
     */
    public string $name;

    /**
     * @Assert\NotBlank
     * @AppUserAssert\Email
     */
    public string $email;

    /**
     * @Assert\NotBlank
     * @AppUserAssert\PasswordStrenght
     */
    public string $password;

    /**
     * @Assert\File(
     *      mimeTypes = {"image/jpeg", "image/png"},
     * )
     */
    public ?UploadedFile $image = null;
}
