<?php

declare(strict_types=1);

namespace App\Story\Query\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use Doctrine\Common\Collections\Collection;

interface StoryRatingRepositoryInterface extends RepositoryInterface
{
    public function populateStories(Collection $stories): void;
}
