<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Repository\Dto\AbstractRepository;
use App\Story\Model\Dto\Story;
use App\Story\Model\Dto\StoryImage;
use App\Story\Model\Dto\StoryThemeFull;
use App\Story\Model\Dto\StoryThemeFullParent;
use App\Story\Model\Dto\StoryThemeMedium;
use App\Story\Query\StoryThemeSearchQuery;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

final class StoryThemeRepository extends AbstractRepository implements StoryThemeRepositoryInterface
{
    public function search(StoryThemeSearchQuery $query): Collection
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, $query->languageId);

        $qb->orderBy('storyTheme.position', Criteria::ASC);

        $datas = $qb->execute()->fetchAll();

        $storyThemes = new ArrayCollection();
        $storyThemeChildrenTmp = [];

        foreach ($datas as $data) {
            if (null === $data['parent_id']) {
                $storyThemes->add(new StoryThemeFullParent(strval($data['id']), strval($data['title']), strval($data['title_slug'])));
            } else {
                $storyThemeChildrenTmp[] = [
                    'parent_id' => strval($data['parent_id']),
                    'story_theme' => new StoryThemeFull(strval($data['id']), strval($data['title']), strval($data['title_slug'])),
                ];
            }
        }

        foreach ($storyThemeChildrenTmp as $storyThemeChildTmp) {
            foreach ($storyThemes as $storyTheme) {
                if ($storyThemeChildTmp['parent_id'] === $storyTheme->getId()) {
                    $storyTheme->addChild($storyThemeChildTmp['story_theme']);
                }
            }
        }

        return $storyThemes;
    }

    public function findChildrenIds(): array
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $qb->select('id')
            ->from('sty_story_theme')
            ->where($qb->expr()->andX(
                $qb->expr()->isNotNull('parent_id'),
                $qb->expr()->eq('activated', ':activated')
            ))
            ->setParameter('activated', true)

        ;

        $datas = $qb->execute()->fetchAll();

        return array_map(function ($data) {
            return $data['id'];
        }, $datas);
    }

    public function populateStoryImages(Collection $storyImages, string $languageId): void
    {
        $storyImageIds = StoryImage::extractIds($storyImages);

        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, $languageId);

        $qb->addSelect('storyImageHasStoryTheme.story_image_id as story_image_id')
            ->join('storyTheme', 'sty_story_image_has_story_theme', 'storyImageHasStoryTheme', $qb->expr()->andX(
                $qb->expr()->eq('storyImageHasStoryTheme.story_theme_id', 'storyTheme.id'),
                $qb->expr()->in('storyImageHasStoryTheme.story_image_id', ':story_image_ids')
            ))
            ->setParameter('story_image_ids', $storyImageIds, Connection::PARAM_STR_ARRAY)
            ->join('storyTheme', 'sty_story_theme', 'storyThemeParent', $qb->expr()->andX(
                $qb->expr()->eq('storyThemeParent.id', 'storyTheme.parent_id'),
            ))
            ->orderBy('storyThemeParent.position', Criteria::ASC)
            ->addOrderBy('storyTheme.position', Criteria::ASC)
        ;

        $datas = $qb->execute()->fetchAll();

        foreach ($datas as $data) {
            foreach ($storyImages as $storyImage) {
                if ($storyImage->getId() === strval($data['story_image_id'])) {
                    $storyTheme = new StoryThemeMedium(strval($data['id']), strval($data['title']), strval($data['title_slug']));
                    $storyImage->addStoryTheme($storyTheme);
                }
            }
        }
    }

    public function populateStories(Collection $stories, string $languageId): void
    {
        $storyIds = Story::extractIds($stories);

        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, $languageId);

        $qb->addSelect('storyHasStoryTheme.story_id as story_id')
            ->join('storyTheme', 'sty_story_has_story_theme', 'storyHasStoryTheme', $qb->expr()->andX(
                $qb->expr()->eq('storyHasStoryTheme.story_theme_id', 'storyTheme.id'),
                $qb->expr()->in('storyHasStoryTheme.story_id', ':story_ids')
            ))
            ->setParameter('story_ids', $storyIds, Connection::PARAM_STR_ARRAY)
            ->join('storyTheme', 'sty_story_theme', 'storyThemeParent', $qb->expr()->andX(
                $qb->expr()->eq('storyThemeParent.id', 'storyTheme.parent_id'),
            ))
            ->orderBy('storyThemeParent.position', Criteria::ASC)
            ->addOrderBy('storyTheme.position', Criteria::ASC)
        ;

        $datas = $qb->execute()->fetchAll();

        foreach ($datas as $data) {
            foreach ($stories as $story) {
                if ($story->getId() === strval($data['story_id'])) {
                    $storyTheme = new StoryThemeMedium(strval($data['id']), strval($data['title']), strval($data['title_slug']));
                    $story->addStoryTheme($storyTheme);
                }
            }
        }
    }

    private function createBaseQueryBuilder(QueryBuilder $qb, string $languageId): void
    {
        $qb->select('storyTheme.id as id', 'storyTheme.parent_id as parent_id')
            ->from('sty_story_theme', 'storyTheme')
            ->addSelect('storyThemeTranslation.title as title', 'storyThemeTranslation.title_slug as title_slug')
            ->join('storyTheme', 'sty_story_theme_translation', 'storyThemeTranslation', $qb->expr()->andX(
                $qb->expr()->eq('storyThemeTranslation.story_theme_id', 'storyTheme.id'),
                $qb->expr()->eq('storyThemeTranslation.language_id', ':language_id')
            ))
            ->setParameter('language_id', $languageId)
            ->where($qb->expr()->eq('storyTheme.activated', ':story_theme_activated'))
            ->setParameter('story_theme_activated', true)
        ;
    }
}
