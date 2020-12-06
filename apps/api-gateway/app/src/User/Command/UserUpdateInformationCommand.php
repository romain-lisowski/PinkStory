<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\CommandInterface;
use App\Form\FormableInterface;
use App\Form\FormableTrait;
use App\Handler\HandlerableInterface;
use App\Handler\HandlerableTrait;
use App\Language\Entity\Language;
use Symfony\Component\Validator\Constraints as Assert;

final class UserUpdateInformationCommand implements CommandInterface, HandlerableInterface, FormableInterface
{
    use FormableTrait;
    use HandlerableTrait;

    /**
     * @Assert\NotBlank
     */
    public string $id;

    /**
     * @Assert\NotBlank
     */
    public string $name;

    /**
     * @Assert\NotBlank
     */
    public Language $language;
}
