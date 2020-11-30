<?php

declare(strict_types=1);

namespace App\Story\Repository;

use App\Story\Entity\StoryThemeTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class StoryThemeTranslationRepository extends ServiceEntityRepository implements StoryThemeTranslationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryThemeTranslation::class);
    }
}
