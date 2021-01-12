<?php

declare(strict_types=1);

namespace App\Story\Repository\Entity;

use App\Story\Model\Entity\Story;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class StoryRepository extends ServiceEntityRepository implements StoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Story::class);
    }

    public function findOne(string $id): Story
    {
        $qb = $this->createQueryBuilder('story');

        $qb->where($qb->expr()->eq('story.id', ':story_id'))
            ->setParameter('story_id', $id)
        ;

        return $qb->getQuery()->getSingleResult();
    }

    public function findOneParent(string $id): Story
    {
        $qb = $this->createQueryBuilder('story');

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('story.id', ':story_id'),
            $qb->expr()->isNull('story.parent')
        ))
            ->setParameter('story_id', $id)
        ;

        return $qb->getQuery()->getSingleResult();
    }
}
