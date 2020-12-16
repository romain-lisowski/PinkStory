<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Language\Model\Dto\LanguageMedium;
use App\Repository\Dto\AbstractRepository;
use App\Story\Model\Dto\StoryFull;
use App\Story\Model\Dto\StoryFullChild;
use App\Story\Model\Dto\StoryFullParent;
use App\Story\Model\Dto\StoryMedium;
use App\Story\Query\StoryGetQuery;
use App\User\Model\Dto\UserMedium;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

final class StoryRepository extends AbstractRepository implements StoryRepositoryInterface
{
    private StoryRatingRepositoryInterface $storyRatingRepository;
    private StoryImageRepositoryInterface $storyImageRepository;
    private StoryThemeRepositoryInterface $storyThemeRepository;

    public function __construct(EntityManagerInterface $entityManager, StoryRatingRepositoryInterface $storyRatingRepository, StoryImageRepositoryInterface $storyImageRepository, StoryThemeRepositoryInterface $storyThemeRepository)
    {
        parent::__construct($entityManager);

        $this->storyRatingRepository = $storyRatingRepository;
        $this->storyImageRepository = $storyImageRepository;
        $this->storyThemeRepository = $storyThemeRepository;
    }

    public function getOne(StoryGetQuery $query): StoryFull
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $this->createBaseQueryBuilder($qb);

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('story.id', ':story_id'),
            $qb->expr()->eq('story.activated', ':story_activated')
        ))
            ->setParameter('story_id', $query->id)
            ->setParameter('story_activated', true)
        ;

        $data = $qb->execute()->fetch();

        if (false === $data) {
            throw new NoResultException();
        }

        $stories = new ArrayCollection();

        $userLanguage = new LanguageMedium(strval($data['user_language_id']));
        $user = new UserMedium(strval($data['user_id']), boolval($data['user_image_defined']), strval($data['user_name']), strval($data['user_name_slug']), new DateTime($data['user_created_at']), $userLanguage);

        $language = new LanguageMedium(strval($data['story_language_id']));

        if (null === $data['story_parent_id']) {
            $story = new StoryFullParent(strval($data['story_id']), strval($data['story_title']), strval($data['story_title_slug']), strval($data['story_content']), new DateTime($data['story_created_at']), $user, $language);
            $stories->add($story);

            $storyChildren = $this->getChildren($story->getId());
            $story->setChildren($storyChildren);
            $stories = new ArrayCollection(array_merge($stories->toArray(), $storyChildren->toArray()));
        } else {
            $storyParent = $this->getParent(strval($data['story_parent_id']));
            $stories->add($storyParent);

            $storyPrevious = null;

            $storyPrevious = $this->getPrevious(strval($data['story_parent_id']), intval($data['story_position']));

            if (null !== $storyPrevious) {
                $stories->add($storyPrevious);
            }

            $storyNext = $this->getNext(strval($data['story_parent_id']), intval($data['story_position']));

            if (null !== $storyNext) {
                $stories->add($storyNext);
            }

            $story = new StoryFullChild(strval($data['story_id']), strval($data['story_title']), strval($data['story_title_slug']), strval($data['story_content']), new DateTime($data['story_created_at']), $user, $language, $storyParent, $storyPrevious, $storyNext);
            $stories->add($story);
        }

        $this->storyRatingRepository->populateStories($stories);

        $this->storyImageRepository->populateStories($stories, $query->languageId);

        $this->storyThemeRepository->populateStories($stories, $query->languageId);

        return $story;
    }

    private function getChildren(string $parentId): Collection
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $this->createBaseQueryBuilder($qb);

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('story.parent_id', ':story_parent_id'),
            $qb->expr()->eq('story.activated', ':story_activated')
        ))
            ->setParameter('story_parent_id', $parentId)
            ->setParameter('story_activated', true)
            ->orderBy('story.position', Criteria::ASC)
        ;

        $datas = $qb->execute()->fetchAll();

        $stories = new ArrayCollection();

        foreach ($datas as $data) {
            $story = $this->populateMedium($data);
            $stories->add($story);
        }

        return $stories;
    }

    private function getParent(string $id): StoryMedium
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $this->createBaseQueryBuilder($qb);

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('story.id', ':story_id'),
            $qb->expr()->eq('story.activated', ':story_activated')
        ))
            ->setParameter('story_id', $id)
            ->setParameter('story_activated', true)
        ;

        $data = $qb->execute()->fetch();

        if (false === $data) {
            throw new NoResultException();
        }

        return $this->populateMedium($data);
    }

    private function getPrevious(string $parentId, int $position): ?StoryMedium
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $this->createBaseQueryBuilder($qb);

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('story.parent_id', ':story_parent_id'),
            $qb->expr()->lt('story.position', ':story_position'),
            $qb->expr()->eq('story.activated', ':story_activated')
        ))
            ->setParameter('story_parent_id', $parentId)
            ->setParameter('story_position', $position)
            ->setParameter('story_activated', true)
            ->orderBy('story.position', Criteria::DESC)
            ->setMaxResults(1)
        ;

        $data = $qb->execute()->fetch();

        if (false === $data) {
            return null;
        }

        return $this->populateMedium($data);
    }

    private function getNext(string $parentId, int $position): ?StoryMedium
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $this->createBaseQueryBuilder($qb);

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('story.parent_id', ':story_parent_id'),
            $qb->expr()->gt('story.position', ':story_position'),
            $qb->expr()->eq('story.activated', ':story_activated')
        ))
            ->setParameter('story_parent_id', $parentId)
            ->setParameter('story_position', $position)
            ->setParameter('story_activated', true)
            ->orderBy('story.position', Criteria::ASC)
            ->setMaxResults(1)
        ;

        $data = $qb->execute()->fetch();

        if (false === $data) {
            return null;
        }

        return $this->populateMedium($data);
    }

    private function createBaseQueryBuilder(QueryBuilder $qb): void
    {
        $qb->select('story.id as story_id', 'story.title as story_title', 'story.title_slug as story_title_slug', 'story.content as story_content', 'story.created_at as story_created_at', 'story.parent_id as story_parent_id', 'story.position as story_position')
            ->from('sty_story', 'story')
            ->addSelect('story_language.id as story_language_id')
            ->join('story', 'lng_language', 'story_language', $qb->expr()->eq('story_language.id', 'story.language_id'))
            ->addSelect('u.id as user_id', 'u.image_defined as user_image_defined', 'u.name as user_name', 'u.name_slug as user_name_slug', 'u.created_at as user_created_at')
            ->join('story', 'usr_user', 'u', $qb->expr()->eq('u.id', 'story.user_id'))
            ->addSelect('u_language.id as user_language_id')
            ->join('u', 'lng_language', 'u_language', $qb->expr()->eq('u_language.id', 'u.language_id'))
        ;
    }

    private function populateMedium(array $data): StoryMedium
    {
        $userLanguage = new LanguageMedium(strval($data['user_language_id']));
        $user = new UserMedium(strval($data['user_id']), boolval($data['user_image_defined']), strval($data['user_name']), strval($data['user_name_slug']), new DateTime($data['user_created_at']), $userLanguage);

        $language = new LanguageMedium(strval($data['story_language_id']));

        return new StoryMedium(strval($data['story_id']), strval($data['story_title']), strval($data['story_title_slug']), strval($data['story_content']), new DateTime($data['story_created_at']), $user, $language);
    }
}
