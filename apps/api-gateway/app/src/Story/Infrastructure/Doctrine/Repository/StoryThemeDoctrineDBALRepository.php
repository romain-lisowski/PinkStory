<?php

declare(strict_types=1);

namespace App\Story\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineDBALRepository;
use App\Story\Query\Model\StoryThemeFull;
use App\Story\Query\Model\StoryThemeFullParent;
use App\Story\Query\Query\StoryThemeSearchQuery;
use App\Story\Query\Repository\StoryThemeRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

final class StoryThemeDoctrineDBALRepository extends AbstractDoctrineDBALRepository implements StoryThemeRepositoryInterface
{
    public function search(StoryThemeSearchQuery $query): Collection
    {
        $qb = $this->createQueryBuilder();

        $qb->select('storyTheme.id as id', 'storyTheme.parent_id as parent_id')
            ->from('sty_story_theme', 'storyTheme')
            ->addSelect('storyThemeTranslation.title as title', 'storyThemeTranslation.title_slug as title_slug')
            ->join('storyTheme', 'sty_story_theme_translation', 'storyThemeTranslation', $qb->expr()->and(
                $qb->expr()->eq('storyThemeTranslation.story_theme_id', 'storyTheme.id'),
                $qb->expr()->eq('storyThemeTranslation.language_id', ':language_id')
            ))
            ->setParameter('language_id', $query->getLanguageId())
        ;

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
}
