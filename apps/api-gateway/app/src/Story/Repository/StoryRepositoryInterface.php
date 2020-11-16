<?php

declare(strict_types=1);

namespace App\Story\Repository;

use App\Story\Entity\Story;

interface StoryRepositoryInterface
{
    public function findOne(string $id): Story;
}
