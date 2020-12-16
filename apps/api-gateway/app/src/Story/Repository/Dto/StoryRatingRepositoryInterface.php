<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use Doctrine\Common\Collections\Collection;

interface StoryRatingRepositoryInterface
{
    public function populateStories(Collection $stories): void;
}
