<?php

declare(strict_types=1);

namespace App\Story\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryRatingUpdateCommand implements CommandInterface
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

    public function __construct(string $storyId, string $userId, int $rate)
    {
        $this->storyId = $storyId;
        $this->userId = $userId;
        $this->rate = $rate;
    }

    public function getStoryId(): string
    {
        return $this->storyId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getRate(): int
    {
        return $this->rate;
    }
}
