<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Language\Model\Dto\LanguageMedium;
use App\Repository\Dto\AbstractRepository;
use App\Story\Model\Dto\StoryFull;
use App\User\Model\Dto\UserMedium;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

final class StoryRepository extends AbstractRepository implements StoryRepositoryInterface
{
    public function findOne(string $id): StoryFull
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $qb->select('story.id as story_id', 'story.title as story_title', 'story.title_slug as story_title_slug', 'story.content as story_content', 'story.created_at as story_created_at', 'story.parent_id as story_parent_id')
            ->from('sty_story', 'story')
            ->addSelect('story_language.id as story_language_id')
            ->join('story', 'lng_language', 'story_language', $qb->expr()->eq('story_language.id', 'story.language_id'))
            ->addSelect('u.id as user_id', 'u.image_defined as user_image_defined', 'u.name as user_name', 'u.name_slug as user_name_slug', 'u.created_at as user_created_at')
            ->join('story', 'usr_user', 'u', $qb->expr()->eq('u.id', 'story.user_id'))
            ->addSelect('u_language.id as user_language_id')
            ->join('u', 'lng_language', 'u_language', $qb->expr()->eq('u_language.id', 'u.language_id'))
            ->where($qb->expr()->eq('story.id', ':story_id'))
            ->setParameter('story_id', $id)
        ;

        $data = $qb->execute()->fetch();

        $stories = new ArrayCollection();

        $userLanguage = new LanguageMedium(strval($data['user_language_id']));
        $user = new UserMedium(strval($data['user_id']), boolval($data['user_image_defined']), strval($data['user_name']), strval($data['user_name_slug']), new DateTime($data['user_created_at']), $userLanguage);

        $language = new LanguageMedium(strval($data['story_language_id']));

        $story = new StoryFull(strval($data['story_id']), strval($data['story_title']), strval($data['story_title_slug']), strval($data['story_content']), new DateTime($data['story_created_at']), $user, $language);
        $stories->add($story);

        return $story;
    }
}
