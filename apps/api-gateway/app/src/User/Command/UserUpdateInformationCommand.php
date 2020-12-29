<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\CommandInterface;
use App\Form\FormableInterface;
use App\Form\FormableTrait;
use App\Handler\HandlerableInterface;
use App\Handler\HandlerableTrait;
use Symfony\Component\Validator\Constraints as Assert;

final class UserUpdateInformationCommand implements CommandInterface, HandlerableInterface, FormableInterface
{
    use HandlerableTrait;
    use FormableTrait;

    /**
     * @Assert\NotBlank
     */
    public string $id = '';

    /**
     * @Assert\NotBlank
     */
    public string $name = '';

    /**
     * @Assert\NotBlank
     * @Assert\Choice(callback={"App\User\Model\UserGender", "getChoices"})
     */
    public string $gender = '';

    /**
     * @Assert\NotBlank
     */
    public string $languageId = '';
}
