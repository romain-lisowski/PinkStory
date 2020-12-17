<?php

declare(strict_types=1);

namespace App\Story\Repository\Entity;

use App\Story\Model\Entity\StoryRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

final class StoryRatingRepository extends ServiceEntityRepository implements StoryRatingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryRating::class);
    }

    public function findOneByStoryAndUser(string $storyId, string $userId): ?StoryRating
    {
        $qb = $this->createQueryBuilder('storyRating');

        $qb->join('storyRating.story', 'story', Join::WITH, $qb->expr()->eq('story.id', ':story_id'))
            ->setParameter('story_id', $storyId)
            ->join('storyRating.user', 'user', Join::WITH, $qb->expr()->eq('user.id', ':user_id'))
            ->setParameter('user_id', $userId)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
