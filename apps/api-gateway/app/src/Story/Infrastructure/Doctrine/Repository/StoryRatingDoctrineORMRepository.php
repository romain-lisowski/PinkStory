<?php

declare(strict_types=1);

namespace App\Story\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineORMRepository;
use App\Story\Domain\Model\StoryRating;
use App\Story\Domain\Repository\StoryRatingRepositoryInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

final class StoryRatingDoctrineORMRepository extends AbstractDoctrineORMRepository implements StoryRatingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryRating::class);
    }

    public function findOneByStoryAndUser(string $storyId, string $userId): ?StoryRating
    {
        try {
            $qb = $this->createQueryBuilder('storyRating')
                ->join('storyRating.story', 'story')
                ->join('storyRating.user', 'user')
            ;

            $qb->where($qb->expr()->andX(
                $qb->expr()->eq('story.id', ':story_id'),
                $qb->expr()->eq('user.id', ':user_id')
            ))->setParameters([
                'story_id' => $storyId,
                'user_id' => $userId,
            ]);

            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}
