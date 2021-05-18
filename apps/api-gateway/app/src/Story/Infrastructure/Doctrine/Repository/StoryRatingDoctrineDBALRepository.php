<?php

declare(strict_types=1);

namespace App\Story\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineDBALRepository;
use App\Story\Query\Model\Story;
use App\Story\Query\Model\StoryRatingUpdate;
use App\Story\Query\Repository\StoryRatingRepositoryInterface;
use App\User\Query\Model\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

final class StoryRatingDoctrineDBALRepository extends AbstractDoctrineDBALRepository implements StoryRatingRepositoryInterface
{
    public function findOneForUpdate(string $storyId, string $userId): ?StoryRatingUpdate
    {
        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb);

        $qb->addSelect('story_id', 'user_id')
            ->where($qb->expr()->and(
                $qb->expr()->eq('story_id', ':story_id'),
                $qb->expr()->eq('user_id', ':user_id')
            ))->setParameters([
                'story_id' => $storyId,
                'user_id' => $userId,
            ]);

        $data = $qb->execute()->fetchAssociative();

        if (false === $data) {
            return null;
        }

        return (new StoryRatingUpdate())
            ->setRate(intval($data['rate']))
            ->setStory((new Story())->setId(strval($data['story_id'])))
            ->setUser((new User())->setId(strval($data['user_id'])))
        ;
    }

    public function populateStories(Collection $stories): void
    {
        $storyIds = Story::extractIds($stories->toArray());

        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb);

        $qb->addSelect('story_id')
            ->where($qb->expr()->in('story_id', ':story_ids'))
            ->setParameter('story_ids', $storyIds, Connection::PARAM_STR_ARRAY)
        ;

        $datas = $qb->execute()->fetchAllAssociative();

        foreach ($datas as $data) {
            foreach ($stories as $story) {
                if ($story->getId() === strval($data['story_id'])) {
                    $story->addRate(intval($data['rate']));

                    break;
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
