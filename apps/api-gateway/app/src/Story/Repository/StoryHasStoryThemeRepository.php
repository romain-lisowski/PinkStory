<?php

declare(strict_types=1);

namespace App\Story\Repository;

use App\Story\Entity\StoryHasStoryTheme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class StoryHasStoryThemeRepository extends ServiceEntityRepository implements StoryHasStoryThemeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryHasStoryTheme::class);
    }
}
