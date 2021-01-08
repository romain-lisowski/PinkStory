<?php

declare(strict_types=1);

namespace App\Story\Repository\Entity;

use App\Story\Model\Entity\StoryImage;

interface StoryImageRepositoryInterface
{
    public function findOne(string $id): StoryImage;
}
