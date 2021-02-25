<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class UserUpdateInformationCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $id;

    /**
     * @Assert\NotBlank
     * @Assert\Choice(callback={"App\User\Domain\Model\UserGender", "getChoices"})
     */
    private string $gender;

    /**
     * @Assert\NotBlank
     */
    private string $name;

    public function __construct(string $id, string $gender, string $name)
    {
        $this->id = $id;
        $this->gender = $gender;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
