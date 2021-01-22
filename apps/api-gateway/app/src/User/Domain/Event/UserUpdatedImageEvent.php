<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\Common\Domain\Event\EventInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class UserUpdatedImageEvent implements EventInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $id;

    /**
     * @Assert\NotBlank
     */
    private string $imagePath;

    public function __construct(string $id, string $imagePath)
    {
        $this->id = $id;
        $this->imagePath = $imagePath;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getImagePath(): string
    {
        return $this->imagePath;
    }
}
