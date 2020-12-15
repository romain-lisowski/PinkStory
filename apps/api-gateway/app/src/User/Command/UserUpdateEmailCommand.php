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

final class UserUpdateEmailCommand implements CommandInterface, HandlerableInterface, FormableInterface
{
    use FormableTrait;
    use HandlerableTrait;

    /**
     * @Assert\NotBlank
     */
    public string $id = '';

    /**
     * @Assert\NotBlank
     * @AppUserAssert\Email
     */
    public string $email = '';
}
