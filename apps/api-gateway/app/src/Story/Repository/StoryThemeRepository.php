<?php

declare(strict_types=1);

namespace App\Story\Repository;

use App\Story\Entity\StoryTheme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class StoryThemeRepository extends ServiceEntityRepository implements StoryThemeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryTheme::class);
    }

    public function findOne(string $id): StoryTheme
    {
        $qb = $this->createQueryBuilder('storyTheme');

        $qb->where($qb->expr()->eq('storyTheme.id', ':story_theme_id'))
            ->setParameter('story_theme_id', $id)
        ;

        return $qb->getQuery()->getSingleResult();
    }
}
