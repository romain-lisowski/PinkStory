<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use App\Common\Infrastructure\Validator\Constraint as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class UserValidateEmailCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     * @AppAssert\Entity(
     *      entityClass = "App\User\Domain\Model\User",
     *      message = "user.validator.constraint.user_not_found"
     * )
     */
    private string $id;

    /**
     * @Assert\NotBlank
     * @Assert\Regex("/^([0-9]{6})$/")
     */
    private string $code;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
