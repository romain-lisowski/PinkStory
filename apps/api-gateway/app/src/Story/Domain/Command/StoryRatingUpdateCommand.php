<?php

declare(strict_types=1);

namespace App\Story\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use App\Common\Infrastructure\Validator\Constraint as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryRatingUpdateCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     * @AppAssert\Entity(
     *      entityClass = "App\Story\Domain\Model\Story",
     *      message = "story.validator.constraint.story_not_found"
     * )
     */
    private string $storyId;

    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     * @AppAssert\Entity(
     *      entityClass = "App\User\Domain\Model\User",
     *      message = "user.validator.constraint.user_not_found"
     * )
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
