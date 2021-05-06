<?php

declare(strict_types=1);

namespace App\Story\Domain\Repository;

use App\Common\Domain\Repository\RepositoryInterface;
use App\Story\Domain\Model\StoryTheme;

interface StoryThemeRepositoryInterface extends RepositoryInterface
{
    /**
     * @throws StoryThemeNoResultException
     */
    public function findOne(string $id): StoryTheme;
}
