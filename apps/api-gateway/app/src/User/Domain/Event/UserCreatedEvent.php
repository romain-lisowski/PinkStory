<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\Common\Domain\Event\EventInterface;
use App\User\Domain\Command\UserCreateCommand;
use Symfony\Component\Validator\Constraints as Assert;

final class UserCreatedEvent implements EventInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $id;

    /**
     * @Assert\NotBlank
     */
    private UserCreateCommand $data;

    public function __construct(string $id, UserCreateCommand $data)
    {
        $this->id = $id;
        $this->data = $data;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getData(): UserCreateCommand
    {
        return $this->data;
    }
}
