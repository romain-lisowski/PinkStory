<?php

declare(strict_types=1);

namespace App\Story\Dto;

use App\Story\Query\StoryImageSearchQuery;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

final class StoryImageRepository implements StoryImageRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private StoryImageTranslationRepositoryInterface $storyImageTranslationRepository;

    public function __construct(EntityManagerInterface $entityManager, StoryImageTranslationRepositoryInterface $storyImageTranslationRepository)
    {
        $this->entityManager = $entityManager;
        $this->storyImageTranslationRepository = $storyImageTranslationRepository;
    }

    public function search(StoryImageSearchQuery $query): Collection
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('id')
            ->from('sty_story_image')
        ;

        $storyImageDatas = $qb->execute()->fetchAll();

        $storyImages = new ArrayCollection();

        foreach ($storyImageDatas as $storyImageData) {
            $storyImage = new StoryImageMedium($storyImageData['id']);
            $storyImages->add($storyImage);
        }

        $this->storyImageTranslationRepository->populateStoryImages($storyImages, $query->language);

        return $storyImages;
    }
}
