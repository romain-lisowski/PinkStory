<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Repository\Dto\AbstractRepository;
use App\Story\Model\Dto\StoryImageMedium;
use App\Story\Query\StoryImageSearchQuery;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class StoryImageRepository extends AbstractRepository implements StoryImageRepositoryInterface
{
    public function search(StoryImageSearchQuery $query): Collection
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $qb->select('storyImage.id as id', 'storyImageTranslation.title as title', 'storyImageTranslation.title_slug as title_slug')
            ->from('sty_story_image', 'storyImage')
            ->join('storyImage', 'sty_story_image_translation', 'storyImageTranslation', $qb->expr()->andX(
                $qb->expr()->eq('storyImageTranslation.story_image_id', 'storyImage.id'),
                $qb->expr()->eq('storyImageTranslation.language_id', ':language_id')
            ))
            ->setParameter('language_id', $query->language->getId())
        ;

        $datas = $qb->execute()->fetchAll();

        $storyImages = new ArrayCollection();

        foreach ($datas as $data) {
            $storyImage = new StoryImageMedium(strval($data['id']), strval($data['title']), strval($data['title_slug']));
            $storyImages->add($storyImage);
        }

        return $storyImages;
    }
}
