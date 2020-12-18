<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Story\Query\StoryImageSearchQuery;
use Doctrine\Common\Collections\Collection;

interface StoryImageRepositoryInterface
{
    public function getBySearch(StoryImageSearchQuery $query): Collection;

    public function populateStories(Collection $stories, string $languageId): void;
}
