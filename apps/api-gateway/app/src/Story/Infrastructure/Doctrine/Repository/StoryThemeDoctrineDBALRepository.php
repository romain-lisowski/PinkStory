<?php

declare(strict_types=1);

namespace App\Story\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineDBALRepository;
use App\Story\Query\Model\StoryImage;
use App\Story\Query\Model\StoryThemeFull;
use App\Story\Query\Model\StoryThemeFullParent;
use App\Story\Query\Model\StoryThemeMedium;
use App\Story\Query\Query\StoryThemeSearchQuery;
use App\Story\Query\Repository\StoryThemeRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

final class StoryThemeDoctrineDBALRepository extends AbstractDoctrineDBALRepository implements StoryThemeRepositoryInterface
{
    public function search(StoryThemeSearchQuery $query): Collection
    {
        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, $query->getLanguageId());

        $qb->orderBy('storyTheme.position', Criteria::ASC);

        $datas = $qb->execute()->fetchAllAssociative();

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

                    break;
                }
            }
        }

        return $storyThemes;
    }

    public function populateStoryImages(Collection $storyImages, string $languageId): void
    {
        $storyImageIds = StoryImage::extractIds($storyImages->toArray());

        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, $languageId);

        $qb->addSelect('storyImageHasStoryTheme.story_image_id as story_image_id')
            ->join('storyTheme', 'sty_story_image_has_story_theme', 'storyImageHasStoryTheme', $qb->expr()->and(
                $qb->expr()->eq('storyImageHasStoryTheme.story_theme_id', 'storyTheme.id'),
                $qb->expr()->in('storyImageHasStoryTheme.story_image_id', ':story_image_ids')
            ))
            ->setParameter('story_image_ids', $storyImageIds, Connection::PARAM_STR_ARRAY)
            ->join('storyTheme', 'sty_story_theme', 'storyThemeParent', $qb->expr()->and(
                $qb->expr()->eq('storyThemeParent.id', 'storyTheme.parent_id'),
            ))
            ->orderBy('storyThemeParent.position', Criteria::ASC)
            ->addOrderBy('storyTheme.position', Criteria::ASC)
        ;

        $datas = $qb->execute()->fetchAllAssociative();

        foreach ($datas as $data) {
            foreach ($storyImages as $storyImage) {
                if ($storyImage->getId() === strval($data['story_image_id'])) {
                    $storyTheme = new StoryThemeMedium(strval($data['id']), strval($data['title']), strval($data['title_slug']));
                    $storyImage->addStoryTheme($storyTheme);

                    break;
                }
            }
        }
    }

    private function createBaseQueryBuilder(QueryBuilder $qb, string $languageId): void
    {
        $qb->select('storyTheme.id as id', 'storyTheme.parent_id as parent_id')
            ->from('sty_story_theme', 'storyTheme')
            ->addSelect('storyThemeTranslation.title as title', 'storyThemeTranslation.title_slug as title_slug')
            ->join('storyTheme', 'sty_story_theme_translation', 'storyThemeTranslation', $qb->expr()->and(
                $qb->expr()->eq('storyThemeTranslation.story_theme_id', 'storyTheme.id'),
                $qb->expr()->eq('storyThemeTranslation.language_id', ':language_id')
            ))
            ->setParameter('language_id', $languageId)
        ;
    }
}
