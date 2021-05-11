<?php

declare(strict_types=1);

namespace App\Story\Domain\Event;

use App\Common\Domain\Event\EventInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryUpdatedChildrenPositionEvent implements EventInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $id;

    /**
     * @Assert\NotNull
     */
    private array $childrenIds;

    public function __construct(string $id, array $childrenIds)
    {
        $this->id = $id;
        $this->childrenIds = $childrenIds;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getChildrenIds(): array
    {
        return $this->childrenIds;
    }
}
