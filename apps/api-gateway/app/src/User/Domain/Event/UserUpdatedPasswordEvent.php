<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\Common\Domain\Event\EventInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class UserUpdatedPasswordEvent implements EventInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $id;

    /**
     * @Assert\NotBlank
     */
    private string $password;

    public function __construct(string $id, string $password)
    {
        $this->id = $id;
        $this->password = $password;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
