<?php

declare(strict_types=1);

namespace App\Story\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineORMRepository;
use App\Story\Domain\Model\Story;
use App\Story\Domain\Repository\StoryNoResultException;
use App\Story\Domain\Repository\StoryRepositoryInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

final class StoryDoctrineORMRepository extends AbstractDoctrineORMRepository implements StoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Story::class);
    }

    public function findOne(string $id): Story
    {
        try {
            $qb = $this->createQueryBuilder('story');

            $qb->where($qb->expr()->eq('story.id', ':story_id'))
                ->setParameter('story_id', $id)
            ;

            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new StoryNoResultException($e);
        }
    }
}
