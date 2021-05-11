<?php

declare(strict_types=1);

namespace App\Story\Domain\Event;

use App\Common\Domain\Event\EventInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryRatingDeletedEvent implements EventInterface
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
