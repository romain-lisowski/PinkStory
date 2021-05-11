<?php

declare(strict_types=1);

namespace App\Story\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryUpdateChildrenPositionCommand implements CommandInterface
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
