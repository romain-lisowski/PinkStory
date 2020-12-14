<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Language\Model\LanguageInterface;
use App\Repository\Dto\AbstractRepository;
use App\Story\Model\Dto\StoryImage;
use App\Story\Model\Dto\StoryThemeMedium;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Connection;

final class StoryThemeRepository extends AbstractRepository implements StoryThemeRepositoryInterface
{
    public function populateStoryImages(Collection $storyImages, LanguageInterface $language): void
    {
        $storyImageIds = StoryImage::extractIds($storyImages);

        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $qb->select('storyTheme.id as id', 'storyThemeTranslation.title as title', 'storyThemeTranslation.title_slug as title_slug', 'storyImageHasStoryTheme.story_image_id as story_image_id')
            ->from('sty_story_image_has_story_theme', 'storyImageHasStoryTheme')
            ->join('storyImageHasStoryTheme', 'sty_story_theme', 'storyTheme', $qb->expr()->andX(
                $qb->expr()->eq('storyTheme.id', 'storyImageHasStoryTheme.story_theme_id'),
                $qb->expr()->in('storyImageHasStoryTheme.story_image_id', ':story_image_ids')
            ))
            ->setParameter('story_image_ids', $storyImageIds, Connection::PARAM_STR_ARRAY)
            ->join('storyTheme', 'sty_story_theme_translation', 'storyThemeTranslation', $qb->expr()->andX(
                $qb->expr()->eq('storyThemeTranslation.story_theme_id', 'storyTheme.id'),
                $qb->expr()->eq('storyThemeTranslation.language_id', ':language_id')
            ))
            ->setParameter('language_id', $language->getId())
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
}
