<?php

declare(strict_types=1);

namespace App\Story\Repository\Entity;

use App\Story\Model\Entity\StoryTheme;

interface StoryThemeRepositoryInterface
{
    public function findOne(string $id): StoryTheme;
}
