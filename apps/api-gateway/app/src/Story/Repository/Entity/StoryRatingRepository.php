<?php

declare(strict_types=1);

namespace App\Story\Repository\Entity;

use App\Story\Model\Entity\StoryRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class StoryRatingRepository extends ServiceEntityRepository implements StoryRatingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryRating::class);
    }
}
