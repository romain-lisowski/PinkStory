<?php

declare(strict_types=1);

namespace App\Story\Query\Query;

use App\Common\Query\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryThemeSearchQuery implements QueryInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $languageId;

    public function __construct(string $languageId)
    {
        $this->languageId = $languageId;
    }

    public function getLanguageId(): string
    {
        return $this->languageId;
    }
}
