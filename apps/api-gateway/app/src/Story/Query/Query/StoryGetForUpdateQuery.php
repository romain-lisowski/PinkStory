<?php

declare(strict_types=1);

namespace App\Story\Query\Query;

use App\Common\Query\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryGetForUpdateQuery implements QueryInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
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
