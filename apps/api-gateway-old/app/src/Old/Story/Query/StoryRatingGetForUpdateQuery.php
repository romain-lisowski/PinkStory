<?php

declare(strict_types=1);

namespace App\Story\Query;

use App\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryRatingGetForUpdateQuery implements QueryInterface
{
    /**
     * @Assert\NotBlank
     */
    public string $storyId = '';

    /**
     * @Assert\NotBlank
     */
    public string $userId = '';
}
