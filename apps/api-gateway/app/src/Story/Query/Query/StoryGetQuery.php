<?php

declare(strict_types=1);

namespace App\Story\Query\Query;

use App\Common\Query\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryGetQuery implements QueryInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $id;

    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $languageId;

    /**
     * @Assert\Uuid
     */
    private ?string $userId;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getLanguageId(): string
    {
        return $this->languageId;
    }

    public function setLanguageId(string $languageId): self
    {
        $this->languageId = $languageId;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
