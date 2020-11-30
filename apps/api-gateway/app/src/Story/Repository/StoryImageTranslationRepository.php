<?php

declare(strict_types=1);

namespace App\Story\Repository;

use App\Story\Entity\StoryImageTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class StoryImageTranslationRepository extends ServiceEntityRepository implements StoryImageTranslationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryImageTranslation::class);
    }
}
