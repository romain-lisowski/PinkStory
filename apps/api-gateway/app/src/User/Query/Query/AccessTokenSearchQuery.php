<?php

declare(strict_types=1);

namespace App\User\Query\Query;

use App\Common\Query\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class AccessTokenSearchQuery implements QueryInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $userId;

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
