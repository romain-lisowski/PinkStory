<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\CommandInterface;
use App\Form\FormableInterface;
use App\Form\FormableTrait;
use App\Handler\HandlerableInterface;
use App\Handler\HandlerableTrait;
use App\User\Validator\Constraints as AppUserAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class UserCreateCommand implements CommandInterface, HandlerableInterface, FormableInterface
{
    use HandlerableTrait;
    use FormableTrait;

    /**
     * @Assert\NotBlank
     */
    public string $name = '';

    /**
     * @Assert\NotBlank
     * @AppUserAssert\Email
     */
    public string $email = '';

    /**
     * @Assert\NotBlank
     * @AppUserAssert\PasswordStrenght
     */
    public string $password = '';

    /**
     * @Assert\NotBlank
     */
    public string $languageId = '';
}
