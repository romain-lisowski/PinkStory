<?php

declare(strict_types=1);

namespace App\Story\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryRatingDeleteCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $storyId;

    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $userId;

    public function __construct(string $storyId, string $userId)
    {
        $this->storyId = $storyId;
        $this->userId = $userId;
    }

    public function getStoryId(): string
    {
        return $this->storyId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
