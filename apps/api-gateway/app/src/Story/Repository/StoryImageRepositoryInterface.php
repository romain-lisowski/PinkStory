<?php

declare(strict_types=1);

namespace App\Story\Repository;

use App\Story\Entity\StoryImage;
use App\Story\Query\StoryImageSearchQuery;
use Doctrine\Common\Collections\Collection;

interface StoryImageRepositoryInterface
{
    public function findOne(string $id): StoryImage;

    public function search(StoryImageSearchQuery $query): Collection;
}
