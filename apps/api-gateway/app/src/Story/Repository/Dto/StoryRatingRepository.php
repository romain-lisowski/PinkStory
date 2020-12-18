<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Repository\Dto\AbstractRepository;
use App\Story\Model\Dto\Story;
use App\Story\Model\Dto\StoryRatingForUpdate;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

final class StoryRatingRepository extends AbstractRepository implements StoryRatingRepositoryInterface
{
    public function getOneForUpdate(string $storyId, string $userId): ?StoryRatingForUpdate
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $this->createBaseQueryBuilder($qb);

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('story_id', ':story_id'),
            $qb->expr()->eq('user_id', ':user_id')
        ))
            ->setParameter('story_id', $storyId)
            ->setParameter('user_id', $userId)
        ;

        $data = $qb->execute()->fetch();

        if (false === $data) {
            return null;
        }

        return new StoryRatingForUpdate(intval($data['rate']));
    }

    public function populateStories(Collection $stories): void
    {
        $storyIds = Story::extractIds($stories);

        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $this->createBaseQueryBuilder($qb);

        $qb->addSelect('story_id')
            ->where($qb->expr()->in('story_id', ':story_ids'))
            ->setParameter('story_ids', $storyIds, Connection::PARAM_STR_ARRAY)
        ;

        $datas = $qb->execute()->fetchAll();

        foreach ($datas as $data) {
            foreach ($stories as $story) {
                if ($story->getId() === strval($data['story_id'])) {
                    $story->addRate(intval($data['rate']));
                }
            }
        }
    }

    private function createBaseQueryBuilder(QueryBuilder $qb): void
    {
        $qb->select('rate')
            ->from('sty_story_rating')
        ;
    }
}
