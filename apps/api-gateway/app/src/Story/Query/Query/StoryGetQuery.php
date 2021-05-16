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

    public function __construct(string $id, string $languageId)
    {
        $this->id = $id;
        $this->languageId = $languageId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLanguageId(): string
    {
        return $this->languageId;
    }
}
