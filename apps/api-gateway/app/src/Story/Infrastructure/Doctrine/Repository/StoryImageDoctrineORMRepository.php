<?php

declare(strict_types=1);

namespace App\Story\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineORMRepository;
use App\Story\Domain\Model\StoryImage;
use App\Story\Domain\Repository\StoryImageNoResultException;
use App\Story\Domain\Repository\StoryImageRepositoryInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

final class StoryImageDoctrineORMRepository extends AbstractDoctrineORMRepository implements StoryImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryImage::class);
    }

    public function findOne(string $id): StoryImage
    {
        try {
            $qb = $this->createQueryBuilder('storyImage');

            $qb->where($qb->expr()->eq('storyImage.id', ':story_image_id'))
                ->setParameter('story_image_id', $id)
            ;

            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new StoryImageNoResultException($e);
        }
    }
}
