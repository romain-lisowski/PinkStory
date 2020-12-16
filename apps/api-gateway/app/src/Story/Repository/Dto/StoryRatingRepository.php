<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Repository\Dto\AbstractRepository;
use App\Story\Model\Dto\Story;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Connection;

final class StoryRatingRepository extends AbstractRepository implements StoryRatingRepositoryInterface
{
    public function populateStories(Collection $stories): void
    {
        $storyIds = Story::extractIds($stories);

        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $qb->select('rate', 'story_id')
            ->from('sty_story_rating')
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
}
