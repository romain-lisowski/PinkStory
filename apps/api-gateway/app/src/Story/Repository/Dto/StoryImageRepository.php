<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Repository\Dto\AbstractRepository;
use App\Story\Model\Dto\Story;
use App\Story\Model\Dto\StoryImageFull;
use App\Story\Model\Dto\StoryImageMedium;
use App\Story\Query\StoryImageSearchQuery;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

final class StoryImageRepository extends AbstractRepository implements StoryImageRepositoryInterface
{
    private StoryThemeRepositoryInterface $storyThemeRepository;

    public function __construct(EntityManagerInterface $entityManager, StoryThemeRepositoryInterface $storyThemeRepository)
    {
        parent::__construct($entityManager);

        $this->storyThemeRepository = $storyThemeRepository;
    }

    public function countBySearch(StoryImageSearchQuery $query): int
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $qb->select('count(storyImage.id) as total')
            ->from('sty_story_image', 'storyImage')
        ;

        $this->filterSearchQueryBuilderByStoryThemes($qb, $query->storyThemeIds);

        $data = $qb->execute()->fetch();

        return intval($data['total']);
    }

    public function getBySearch(StoryImageSearchQuery $query): Collection
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, $query->languageId);

        $qb->orderBy('storyImage.created_at', Criteria::DESC)
            ->setMaxResults($query->limit)
            ->setFirstResult($query->offset)
        ;

        $this->filterSearchQueryBuilderByStoryThemes($qb, $query->storyThemeIds);

        $datas = $qb->execute()->fetchAll();

        $storyImages = new ArrayCollection();

        foreach ($datas as $data) {
            $storyImage = new StoryImageFull(strval($data['id']), strval($data['title']), strval($data['title_slug']));
            $storyImages->add($storyImage);
        }

        $this->storyThemeRepository->populateStoryImages($storyImages, $query->languageId);

        return $storyImages;
    }

    public function populateStories(Collection $stories, string $languageId): void
    {
        $storyIds = Story::extractIds($stories);

        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, $languageId);

        $qb->addSelect('story.id as story_id')
            ->join('storyImage', 'sty_story', 'story', $qb->expr()->andX(
                $qb->expr()->eq('story.story_image_id', 'storyImage.id'),
                $qb->expr()->in('story.id', ':story_ids')
            ))
            ->setParameter('story_ids', $storyIds, Connection::PARAM_STR_ARRAY)
        ;

        $datas = $qb->execute()->fetchAll();

        foreach ($datas as $data) {
            foreach ($stories as $story) {
                if ($story->getId() === strval($data['story_id'])) {
                    $storyImage = new StoryImageMedium(strval($data['id']), strval($data['title']), strval($data['title_slug']));
                    $story->setStoryImage($storyImage);
                }
            }
        }
    }

    private function filterSearchQueryBuilderByStoryThemes(QueryBuilder $qb, array $storyThemeIds = [])
    {
        if (count($storyThemeIds) > 0) {
            $subQb = $this->getEntityManager()->getConnection()->createQueryBuilder();
            $subQb->select('story_theme_id')
                ->from('sty_story_image_has_story_theme', 'storyImageHasStoryTheme')
                ->where($subQb->expr()->eq('storyImageHasStoryTheme.story_image_id', 'storyImage.id'))
            ;

            $i = 0;
            foreach ($storyThemeIds as $storyThemeId) {
                $qb->andWhere($qb->expr()->in(':story_theme_id_'.$i, $subQb->getSQL()))
                    ->setParameter('story_theme_id_'.$i, $storyThemeId)
                ;

                ++$i;
            }
        }
    }

    private function createBaseQueryBuilder(QueryBuilder $qb, string $languageId): void
    {
        $qb->select('storyImage.id as id')
            ->from('sty_story_image', 'storyImage')
            ->addSelect('storyImageTranslation.title as title', 'storyImageTranslation.title_slug as title_slug')
            ->join('storyImage', 'sty_story_image_translation', 'storyImageTranslation', $qb->expr()->andX(
                $qb->expr()->eq('storyImageTranslation.story_image_id', 'storyImage.id'),
                $qb->expr()->eq('storyImageTranslation.language_id', ':language_id')
            ))
            ->setParameter('language_id', $languageId)
        ;
    }
}
