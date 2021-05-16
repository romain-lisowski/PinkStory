<?php

declare(strict_types=1);

namespace App\Story\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineORMRepository;
use App\Story\Domain\Model\StoryImageHasStoryTheme;
use Doctrine\Persistence\ManagerRegistry;

final class StoryImageHasStoryThemeDoctrineORMRepository extends AbstractDoctrineORMRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryImageHasStoryTheme::class);
    }
}
