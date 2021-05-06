<?php

declare(strict_types=1);

namespace App\Story\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineORMRepository;
use App\Story\Domain\Model\StoryTheme;
use App\Story\Domain\Repository\StoryThemeNoResultException;
use App\Story\Domain\Repository\StoryThemeRepositoryInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

final class StoryThemeDoctrineORMRepository extends AbstractDoctrineORMRepository implements StoryThemeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryTheme::class);
    }

    public function findOne(string $id): StoryTheme
    {
        try {
            $qb = $this->createQueryBuilder('storyTheme');

            $qb->where($qb->expr()->eq('storyTheme.id', ':story_theme_id'))
                ->setParameter('story_theme_id', $id)
            ;

            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new StoryThemeNoResultException($e);
        }
    }
}
