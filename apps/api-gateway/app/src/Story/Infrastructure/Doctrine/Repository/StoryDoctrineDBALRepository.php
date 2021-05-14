<?php

declare(strict_types=1);

namespace App\Story\Infrastructure\Doctrine\Repository;

use App\Common\Domain\Translation\TranslatorInterface;
use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineDBALRepository;
use App\Language\Query\Model\Language;
use App\Language\Query\Model\LanguageMedium;
use App\Story\Domain\Repository\StoryNoResultException;
use App\Story\Query\Model\Story;
use App\Story\Query\Model\StoryImage;
use App\Story\Query\Model\StoryImageMedium;
use App\Story\Query\Model\StoryMedium;
use App\Story\Query\Model\StoryMediumChild;
use App\Story\Query\Model\StoryMediumParent;
use App\Story\Query\Model\StoryTheme;
use App\Story\Query\Model\StoryThemeMedium;
use App\Story\Query\Model\StoryUpdate;
use App\Story\Query\Query\StoryGetForUpdateQuery;
use App\Story\Query\Query\StorySearchQuery;
use App\Story\Query\Repository\StoryImageRepositoryInterface;
use App\Story\Query\Repository\StoryRatingRepositoryInterface;
use App\Story\Query\Repository\StoryRepositoryInterface;
use App\Story\Query\Repository\StoryThemeRepositoryInterface;
use App\User\Domain\Model\UserGender;
use App\User\Query\Model\User;
use App\User\Query\Model\UserMedium;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

final class StoryDoctrineDBALRepository extends AbstractDoctrineDBALRepository implements StoryRepositoryInterface
{
    private StoryImageRepositoryInterface $storyImageRepository;
    private StoryRatingRepositoryInterface $storyRatingRepository;
    private StoryThemeRepositoryInterface $storyThemeRepository;
    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, StoryImageRepositoryInterface $storyImageRepository, StoryRatingRepositoryInterface $storyRatingRepository, StoryThemeRepositoryInterface $storyThemeRepository, TranslatorInterface $translator)
    {
        parent::__construct($entityManager);

        $this->storyImageRepository = $storyImageRepository;
        $this->storyRatingRepository = $storyRatingRepository;
        $this->storyThemeRepository = $storyThemeRepository;
        $this->translator = $translator;
    }

    public function findOneForUpdate(StoryGetForUpdateQuery $query): StoryUpdate
    {
        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, StoryUpdate::class);

        $qb->addSelect('story.content as story_content')
            ->andWhere($qb->expr()->eq('story.id', ':story_id'))
            ->setParameter('story_id', $query->getId())
        ;

        $data = $qb->execute()->fetchAssociative();

        if (false === $data) {
            throw new StoryNoResultException();
        }

        $stories = new ArrayCollection();

        $user = (new User())
            ->setId(strval($data['user_id']))
        ;

        $language = (new Language())
            ->setId(strval($data['story_language_id']))
        ;

        $story = (new StoryUpdate())
            ->setId(strval($data['story_id']))
            ->setTitle(strval($data['story_title']))
            ->setExtract(strval($data['story_extract']))
            ->setContent(strval($data['story_content']))
            ->setUser($user)
            ->setLanguage($language)
        ;

        $stories->add($story);

        $this->storyImageRepository->populateStories($stories, StoryImage::class);

        $this->storyThemeRepository->populateStories($stories, StoryTheme::class);

        return $story;
    }

    public function findBySearch(StorySearchQuery $query): Collection
    {
        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, StoryMedium::class);

        $qb->andWhere($qb->expr()->in('story.language_id', ':story_language_id'))
            ->setParameter('story_language_id', $query->getReadingLanguageIds(), Connection::PARAM_STR_ARRAY)
        ;

        $this->filterSearchQueryBuilderByStoryThemes($qb, $query->getStoryThemeIds());

        if (null !== $query->getUserId()) {
            $qb->andWhere($qb->expr()->eq('story.user_id', ':user_id'))
                ->setParameter('user_id', $query->getUserId())
            ;
        }

        if (StorySearchQuery::TYPE_PARENT === $query->getType()) {
            $qb->andWhere($qb->expr()->isNull('story.parent_id'));
        } elseif (StorySearchQuery::TYPE_CHILD === $query->getType()) {
            $qb->andWhere($qb->expr()->isNotNull('story.parent_id'));
        }

        if (StorySearchQuery::ORDER_POPULAR === $query->getOrder()) {
            $subQb = $this->createQueryBuilder();
            $subQb->select('avg(rate)')
                ->from('sty_story_rating', 'storyRating')
                ->where($subQb->expr()->eq('storyRating.story_id', 'story.id'))
                ->groupBy('story_id')
            ;
            $qb->addSelect('coalesce(('.$subQb->getSQL().'), 0) as story_rate');

            $qb->orderBy('story_rate', $query->getSort());
            $qb->addOrderBy('story.created_at', Criteria::DESC);
        } elseif (StorySearchQuery::ORDER_CREATED_AT === $query->getOrder()) {
            $qb->orderBy('story.created_at', $query->getSort());
        }

        $qb->setMaxResults($query->getLimit())
            ->setFirstResult($query->getOffset())
        ;

        $datas = $qb->execute()->fetchAllAssociative();

        $stories = new ArrayCollection();

        foreach ($datas as $data) {
            $story = $this->populateMedium($data);

            $stories->add($story);
        }

        $this->storyRatingRepository->populateStories($stories);

        $this->storyImageRepository->populateStories($stories, StoryImageMedium::class, $query->getLanguageId());

        $this->storyThemeRepository->populateStories($stories, StoryThemeMedium::class, $query->getLanguageId());

        $this->populateStoryMediumParents($stories);

        $this->populateStoryMediumChildren($stories);

        return $stories;
    }

    public function countBySearch(StorySearchQuery $query): int
    {
        $qb = $this->createQueryBuilder();

        $qb->select('count(story.id) as total')
            ->from('sty_story', 'story')
        ;

        $qb->andWhere($qb->expr()->in('story.language_id', ':story_language_id'))
            ->setParameter('story_language_id', $query->getReadingLanguageIds(), Connection::PARAM_STR_ARRAY)
        ;

        $this->filterSearchQueryBuilderByStoryThemes($qb, $query->getStoryThemeIds());

        if (null !== $query->getUserId()) {
            $qb->andWhere($qb->expr()->eq('story.user_id', ':user_id'))
                ->setParameter('user_id', $query->getUserId())
            ;
        }

        if (StorySearchQuery::TYPE_PARENT === $query->getType()) {
            $qb->andWhere($qb->expr()->isNull('story.parent_id'));
        } elseif (StorySearchQuery::TYPE_CHILD === $query->getType()) {
            $qb->andWhere($qb->expr()->isNotNull('story.parent_id'));
        }

        $data = $qb->execute()->fetchAssociative();

        return intval($data['total']);
    }

    private function createBaseQueryBuilder(QueryBuilder $qb, string $storyClass = Story::class): void
    {
        $qb->select('story.id as story_id')
            ->from('sty_story', 'story')
        ;

        if (true === in_array($storyClass, [StoryMedium::class, StoryUpdate::class])) {
            $qb->addSelect('story.title as story_title', 'story.extract as story_extract')
                ->addSelect('story_language.id as story_language_id')
                ->join('story', 'lng_language', 'story_language', $qb->expr()->eq('story_language.id', 'story.language_id'))
                ->addSelect('u.id as user_id')
                ->join('story', 'usr_user', 'u', $qb->expr()->eq('u.id', 'story.user_id'))
            ;
        }

        if (true === in_array($storyClass, [StoryMedium::class])) {
            $qb->addSelect('story.title_slug as story_title_slug', 'story.created_at as story_created_at', 'story.parent_id as story_parent_id', 'story.position as story_position')
                ->addSelect('story_language.title as story_language_title', 'story_language.locale as story_language_locale')
                ->addSelect('u.gender as user_gender', 'u.name as user_name', 'u.name_slug as user_name_slug', 'u.image_defined as user_image_defined', 'u.created_at as user_created_at')
                ->addSelect('u_language.id as user_language_id', 'u_language.title as user_language_title', 'u_language.locale as user_language_locale')
                ->join('u', 'lng_language', 'u_language', $qb->expr()->eq('u_language.id', 'u.language_id'))
            ;
        }
    }

    private function filterSearchQueryBuilderByStoryThemes(QueryBuilder $qb, array $storyThemeIds = [])
    {
        if (count($storyThemeIds) > 0) {
            $subQb = $this->createQueryBuilder();
            $subQb->select('story_theme_id')
                ->from('sty_story_has_story_theme', 'storyHasStoryTheme')
                ->where($subQb->expr()->eq('storyHasStoryTheme.story_id', 'story.id'))
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

    private function populateMedium(array $data): StoryMedium
    {
        $userLanguage = (new LanguageMedium())
            ->setId(strval($data['user_language_id']))
            ->setTitle(strval($data['user_language_title']))
            ->setLocale(strval($data['user_language_locale']))
        ;

        $user = (new UserMedium())
            ->setId(strval($data['user_id']))
            ->setGender(strval($data['user_gender']))
            ->setGenderReading(UserGender::getReadingChoice(strval($data['user_gender']), $this->translator))
            ->setName(strval($data['user_name']))
            ->setNameSlug(strval($data['user_name_slug']))
            ->setImageDefined(boolval($data['user_image_defined']))
            ->setCreatedAt(new \DateTime(strval($data['user_created_at'])))
            ->setLanguage($userLanguage)
        ;

        $language = (new LanguageMedium())
            ->setId(strval($data['story_language_id']))
            ->setTitle(strval($data['story_language_title']))
            ->setLocale(strval($data['story_language_locale']))
        ;

        if (null === $data['story_parent_id']) {
            return (new StoryMediumParent())
                ->setId(strval($data['story_id']))
                ->setTitle(strval($data['story_title']))
                ->setTitleSlug(strval($data['story_title_slug']))
                ->setExtract(strval($data['story_extract']))
                ->setCreatedAt(new \DateTime(strval($data['story_created_at'])))
                ->setUser($user)
                ->setLanguage($language)
            ;
        }

        return (new StoryMediumChild())
            ->setId(strval($data['story_id']))
            ->setTitle(strval($data['story_title']))
            ->setTitleSlug(strval($data['story_title_slug']))
            ->setExtract(strval($data['story_extract']))
            ->setCreatedAt(new \DateTime(strval($data['story_created_at'])))
            ->setUser($user)
            ->setLanguage($language)
        ;
    }

    private function populateStoryMediumParents(Collection $stories): void
    {
        $storyMediumParents = $stories->filter(function ($story) {
            return $story instanceof StoryMediumParent;
        });
        $storyMediumParentIds = Story::extractIds($storyMediumParents->toArray());

        $qb = $this->createQueryBuilder();

        $qb->select('count(id) as total', 'parent_id')
            ->from('sty_story')
            ->where($qb->expr()->in('parent_id', ':parent_ids'))
            ->setParameter('parent_ids', $storyMediumParentIds, Connection::PARAM_STR_ARRAY)
            ->groupBy('parent_id')
        ;

        $datas = $qb->execute()->fetchAllAssociative();

        foreach ($datas as $data) {
            foreach ($storyMediumParents as $storyMediumParent) {
                if ($storyMediumParent->getId() === strval($data['parent_id'])) {
                    $storyMediumParent->setChildrenTotal(intval($data['total']));

                    break;
                }
            }
        }
    }

    private function populateStoryMediumChildren(Collection $stories): void
    {
        $storyMediumChildren = $stories->filter(function ($story) {
            return $story instanceof StoryMediumChild;
        });
        $storyMediumChildIds = Story::extractIds($storyMediumChildren->toArray());

        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, StoryMedium::class);

        $qb->addSelect('child_story.id as child_story_id')
            ->join('story', 'sty_story', 'child_story', $qb->expr()->and(
                $qb->expr()->eq('child_story.parent_id', 'story.id'),
                $qb->expr()->in('child_story.id', ':story_id')
            ))
            ->setParameter('story_id', $storyMediumChildIds, Connection::PARAM_STR_ARRAY)
        ;

        $datas = $qb->execute()->fetchAllAssociative();

        $storyParents = new ArrayCollection();

        foreach ($datas as $data) {
            foreach ($storyMediumChildren as $storyMediumChild) {
                if ($storyMediumChild->getId() === strval($data['child_story_id'])) {
                    $storyParent = $this->populateMedium($data);
                    $storyMediumChild->setParent($storyParent);
                    $storyParents->add($storyParent);

                    break;
                }
            }
        }

        $this->populateStoryMediumParents($storyParents);
    }
}
