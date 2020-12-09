<?php

declare(strict_types=1);

namespace App\Story\Repository;

use App\Story\Entity\StoryImage;
use App\Story\Query\StoryImageSearchQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

final class StoryImageRepository extends ServiceEntityRepository implements StoryImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryImage::class);
    }

    public function findOne(string $id): StoryImage
    {
        $qb = $this->createQueryBuilder('storyImage');

        $qb->where($qb->expr()->eq('storyImage.id', ':story_image_id'))
            ->setParameter('story_image_id', $id)
        ;

        return $qb->getQuery()->getSingleResult();
    }

    public function search(StoryImageSearchQuery $query): Collection
    {
        $qb = $this->createQueryBuilder('storyImage');

        $storyImages = $qb->getQuery()->getResult();

        return new ArrayCollection($storyImages);
    }
}
