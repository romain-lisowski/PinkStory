<?php

declare(strict_types=1);

namespace App\Story\Model\Dto;

use App\Model\Dto\DtoInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class StoryRating implements DtoInterface
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    private int $rate;

    public function __construct(int $rate = 0)
    {
        $this->rate = $rate;
    }

    public function getRate(): int
    {
        return $this->rate;
    }
}
