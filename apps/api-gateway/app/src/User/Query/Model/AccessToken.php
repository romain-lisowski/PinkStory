<?php

declare(strict_types=1);

namespace App\User\Query\Model;

use App\Common\Query\Model\AbstractModel;
use App\User\Domain\Model\UserableInterface;
use App\User\Domain\Model\UserInterface;

class AccessToken extends AbstractModel implements UserableInterface
{
    private string $id;
    private User $user;

    public function __construct(string $id, User $user)
    {
        $this->id = $id;
        $this->user = $user;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
