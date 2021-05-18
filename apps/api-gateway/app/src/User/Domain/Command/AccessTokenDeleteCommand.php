<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use App\Common\Infrastructure\Validator\Constraint as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class AccessTokenDeleteCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     * @AppAssert\Entity(
     *      entityClass = "App\User\Domain\Model\AccessToken",
     *      message = "access_token.validator.constraint.access_token_not_found"
     * )
     */
    private string $id;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
