<?php

declare(strict_types=1);

namespace App\Story\Repository\Entity;

use App\Story\Model\Entity\Story;

interface StoryRepositoryInterface
{
    public function findOne(string $id): Story;

    public function findOneParent(string $id): Story;
}
