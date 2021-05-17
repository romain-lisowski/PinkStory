<?php

declare(strict_types=1);

namespace App\Story\Domain\Event;

use App\Common\Domain\Event\EventInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryRatingUpdatedEvent implements EventInterface
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

    /**
     * @Assert\NotBlank
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     * )
     */
    private int $rate;

    public function getStoryId(): string
    {
        return $this->storyId;
    }

    public function setStoryId(string $storyId): self
    {
        $this->storyId = $storyId;

        return $this;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getRate(): int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
