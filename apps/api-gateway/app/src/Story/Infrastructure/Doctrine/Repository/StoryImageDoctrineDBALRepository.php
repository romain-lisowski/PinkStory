<?php

declare(strict_types=1);

namespace App\Story\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineDBALRepository;
use App\Story\Query\Model\Story;
use App\Story\Query\Model\StoryImageFull;
use App\Story\Query\Model\StoryImageMedium;
use App\Story\Query\Query\StoryImageSearchQuery;
use App\Story\Query\Repository\StoryImageRepositoryInterface;
use App\Story\Query\Repository\StoryThemeRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

final class StoryImageDoctrineDBALRepository extends AbstractDoctrineDBALRepository implements StoryImageRepositoryInterface
{
    private StoryThemeRepositoryInterface $storyThemeRepository;

    public function __construct(EntityManagerInterface $entityManager, StoryThemeRepositoryInterface $storyThemeRepository)
    {
        parent::__construct($entityManager);

        $this->storyThemeRepository = $storyThemeRepository;
    }

    public function findBySearch(StoryImageSearchQuery $query): Collection
    {
        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, $query->getLanguageId());

        $this->filterSearchQueryBuilderByStoryThemes($qb, $query->getStoryThemeIds());

        $qb->orderBy('storyImage.created_at', Criteria::DESC)
            ->setMaxResults($query->getLimit())
            ->setFirstResult($query->getOffset())
        ;

        $datas = $qb->execute()->fetchAllAssociative();

        $storyImages = new ArrayCollection();

        foreach ($datas as $data) {
            $storyImage = (new StoryImageFull())
                ->setId(strval($data['id']))
                ->setTitle(strval($data['title']))
                ->setTitleSlug(strval($data['title_slug']))
            ;

            $storyImages->add($storyImage);
        }

        $this->storyThemeRepository->populateStoryImages($storyImages, $query->getLanguageId());

        return $storyImages;
    }

    public function countBySearch(StoryImageSearchQuery $query): int
    {
        $qb = $this->createQueryBuilder();

        $qb->select('count(storyImage.id) as total')
            ->from('sty_story_image', 'storyImage')
        ;

        $this->filterSearchQueryBuilderByStoryThemes($qb, $query->getStoryThemeIds());

        $data = $qb->execute()->fetchAssociative();

        return intval($data['total']);
    }

    public function populateStories(Collection $stories, string $languageId): void
    {
        $storyIds = Story::extractIds($stories->toArray());

        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, $languageId);

        $qb->addSelect('story.id as story_id')
            ->join('storyImage', 'sty_story', 'story', $qb->expr()->and(
                $qb->expr()->eq('story.story_image_id', 'storyImage.id'),
                $qb->expr()->in('story.id', ':story_ids')
            ))
            ->setParameter('story_ids', $storyIds, Connection::PARAM_STR_ARRAY)
        ;

        $datas = $qb->execute()->fetchAllAssociative();

        foreach ($datas as $data) {
            foreach ($stories as $story) {
                if ($story->getId() === strval($data['story_id'])) {
                    $storyImage = (new StoryImageMedium())
                        ->setId(strval($data['id']))
                        ->setTitle(strval($data['title']))
                        ->setTitleSlug(strval($data['title_slug']))
                    ;

                    $story->setStoryImage($storyImage);

                    break;
                }
            }
        }
    }

    private function createBaseQueryBuilder(QueryBuilder $qb, string $languageId): void
    {
        $qb->select('storyImage.id as id')
            ->from('sty_story_image', 'storyImage')
            ->addSelect('storyImageTranslation.title as title', 'storyImageTranslation.title_slug as title_slug')
            ->join('storyImage', 'sty_story_image_translation', 'storyImageTranslation', $qb->expr()->and(
                $qb->expr()->eq('storyImageTranslation.story_image_id', 'storyImage.id'),
                $qb->expr()->eq('storyImageTranslation.language_id', ':language_id')
            ))
            ->setParameter('language_id', $languageId)
        ;
    }

    private function filterSearchQueryBuilderByStoryThemes(QueryBuilder $qb, array $storyThemeIds = [])
    {
        if (count($storyThemeIds) > 0) {
            $subQb = $this->createQueryBuilder();
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
}
