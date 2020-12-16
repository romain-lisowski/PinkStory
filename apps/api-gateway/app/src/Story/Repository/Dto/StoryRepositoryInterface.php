<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Story\Model\Dto\StoryFull;

interface StoryRepositoryInterface
{
    public function findOne(string $id): StoryFull;
}
