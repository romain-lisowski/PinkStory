<?php

declare(strict_types=1);

namespace App\Story\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use App\Common\Infrastructure\Validator\Constraint as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryDeleteCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     * @AppAssert\Entity(
     *      entityClass = "App\Story\Domain\Model\Story",
     *      message = "story.validator.constraint.story_not_found"
     * )
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
