<?php

declare(strict_types=1);

namespace App\User\Query\Model;

use App\Common\Domain\Model\IdentifiableInterface;
use App\Common\Domain\Model\IdentifiableTrait;
use App\Common\Query\Model\AbstractModel;
use App\User\Domain\Model\UserableInterface;
use App\User\Domain\Model\UserInterface;

final class AccessToken extends AbstractModel implements IdentifiableInterface, UserableInterface
{
    use IdentifiableTrait;

    private string $id;
    private User $user;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
