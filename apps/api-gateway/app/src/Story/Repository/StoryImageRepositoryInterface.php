<?php

declare(strict_types=1);

namespace App\Story\Repository;

use App\Story\Entity\StoryImage;

interface StoryImageRepositoryInterface
{
    public function findOne(string $id): StoryImage;
}
