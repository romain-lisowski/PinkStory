<?php

declare(strict_types=1);

namespace App\Story\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use App\Story\Query\Query\StoryThemeSearchQuery;
use Doctrine\Common\Collections\Collection;

interface StoryThemeRepositoryInterface extends RepositoryInterface
{
    public function findBySearch(StoryThemeSearchQuery $query): Collection;

    public function populateStoryImages(Collection $storyImages, string $languageId): void;
}
