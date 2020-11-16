<?php

declare(strict_types=1);

namespace App\Story\Repository;

use App\Story\Entity\StoryImageHasStoryTheme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class StoryImageHasStoryThemeRepository extends ServiceEntityRepository implements StoryImageHasStoryThemeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryImageHasStoryTheme::class);
    }
}
