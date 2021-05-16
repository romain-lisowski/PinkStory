<?php

declare(strict_types=1);

namespace App\Story\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use App\Story\Query\Model\StoryTheme;
use App\Story\Query\Query\StoryThemeSearchQuery;
use Doctrine\Common\Collections\Collection;

interface StoryThemeRepositoryInterface extends RepositoryInterface
{
    public function findBySearch(StoryThemeSearchQuery $query): Collection;

    public function populateStories(Collection $stories, string $storyThemeClass = StoryTheme::class, ?string $languageId = null): void;

    public function populateStoryImages(Collection $storyImages, string $storyThemeClass = StoryTheme::class, ?string $languageId = null): void;
}
