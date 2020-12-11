<?php

declare(strict_types=1);

namespace App\Story\Dto;

use App\Language\Model\Entity\Language;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

final class StoryImageTranslationRepository implements StoryImageTranslationRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function populateStoryImages(Collection $storyImages, Language $language): void
    {
        $storyImageIds = StoryImage::extractIds($storyImages);

        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('title', 'title_slug', 'story_image_id')
            ->from('sty_story_image_translation')
        ;

        $qb->where($qb->expr()->andX(
            $qb->expr()->in('story_image_id', ':story_image_ids'),
            $qb->expr()->in('language_id', ':language_id')
        ))
            ->setParameter('story_image_ids', $storyImageIds, Connection::PARAM_STR_ARRAY)
            ->setParameter('language_id', $language->getId())
        ;

        $storyImageTranslationDatas = $qb->execute()->fetchAll();

        foreach ($storyImageTranslationDatas as $storyImageTranslationData) {
            foreach ($storyImages as $storyImage) {
                if ($storyImage->id === $storyImageTranslationData['story_image_id']) {
                    $storyImage->title = $storyImageTranslationData['title'];
                    $storyImage->titleSlug = $storyImageTranslationData['title_slug'];
                }
            }
        }
    }
}
