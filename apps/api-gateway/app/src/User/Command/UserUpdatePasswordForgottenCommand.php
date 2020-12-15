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

final class UserUpdatePasswordForgottenCommand implements CommandInterface, HandlerableInterface, FormableInterface
{
    use FormableTrait;
    use HandlerableTrait;

    /**
     * @Assert\NotBlank
     */
    public string $secret = '';

    /**
     * @Assert\NotBlank
     * @AppUserAssert\PasswordStrenght
     */
    public string $password = '';
}
