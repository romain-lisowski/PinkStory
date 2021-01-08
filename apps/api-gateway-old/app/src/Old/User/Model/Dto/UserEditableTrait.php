<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\User\Model\UserEditableTrait as ModelUserEditableTrait;
use App\User\Model\UserInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

trait UserEditableTrait
{
    use ModelUserEditableTrait;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private bool $editable = false;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private UserInterface $user;
}
