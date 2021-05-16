<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Repository;

use App\Common\Domain\Translation\TranslatorInterface;
use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineDBALRepository;
use App\Language\Query\Model\Language;
use App\Language\Query\Model\LanguageCurrent;
use App\Language\Query\Model\LanguageMedium;
use App\Language\Query\Repository\LanguageRepositoryInterface;
use App\User\Domain\Model\UserGender;
use App\User\Domain\Model\UserStatus;
use App\User\Domain\Repository\UserNoResultException;
use App\User\Query\Model\UserCurrent;
use App\User\Query\Model\UserFull;
use App\User\Query\Model\UserUpdate;
use App\User\Query\Query\UserGetForUpdateQuery;
use App\User\Query\Query\UserGetQuery;
use App\User\Query\Repository\UserRepositoryInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

final class UserDoctrineDBALRepository extends AbstractDoctrineDBALRepository implements UserRepositoryInterface
{
    private LanguageRepositoryInterface $languageRepository;
    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, LanguageRepositoryInterface $languageRepository, TranslatorInterface $translator)
    {
        parent::__construct($entityManager);

        $this->languageRepository = $languageRepository;
        $this->translator = $translator;
    }

    public function findOne(UserGetQuery $query): UserFull
    {
        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb);

        $qb->addSelect('language.title as language_title', 'language.locale as language_locale')
            ->andWhere($qb->expr()->eq('u.id', ':user_id'))
            ->setParameter('user_id', $query->getId())
        ;

        $data = $qb->execute()->fetchAssociative();

        if (false === $data) {
            throw new UserNoResultException();
        }

        $language = (new LanguageMedium())
            ->setId(strval($data['language_id']))
            ->setTitle(strval($data['language_title']))
            ->setLocale(strval($data['language_locale']))
        ;

        $user = (new UserFull())
            ->setId(strval($data['user_id']))
            ->setGender(strval($data['user_gender']))
            ->setGenderReading(UserGender::getReadingChoice(strval($data['user_gender']), $this->translator))
            ->setName(strval($data['user_name']))
            ->setNameSlug(strval($data['user_name_slug']))
            ->setImageDefined(boolval($data['user_image_defined']))
            ->setCreatedAt(new \DateTime(strval($data['user_created_at'])))
            ->setLanguage($language)
        ;

        $this->languageRepository->populateUserReadingLanguages($user, LanguageMedium::class);

        return $user;
    }

    public function findOneForUpdate(UserGetForUpdateQuery $query): UserUpdate
    {
        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb);

        $qb->addSelect('u.email as user_email')
            ->andWhere($qb->expr()->eq('u.id', ':user_id'))
            ->setParameter('user_id', $query->getId())
        ;

        $data = $qb->execute()->fetchAssociative();

        if (false === $data) {
            throw new UserNoResultException();
        }

        $language = (new Language())
            ->setId(strval($data['language_id']))
        ;

        $user = (new UserUpdate())
            ->setId(strval($data['user_id']))
            ->setGender(strval($data['user_gender']))
            ->setGenderReading(UserGender::getReadingChoice(strval($data['user_gender']), $this->translator))
            ->setName(strval($data['user_name']))
            ->setNameSlug(strval($data['user_name_slug']))
            ->setEmail(strval($data['user_email']))
            ->setImageDefined(boolval($data['user_image_defined']))
            ->setCreatedAt(new \DateTime(strval($data['user_created_at'])))
            ->setLanguage($language)
        ;

        $this->languageRepository->populateUserReadingLanguages($user, Language::class);

        return $user;
    }

    public function findOneForCurrent(string $id): UserCurrent
    {
        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb);

        $qb->addSelect('u.role as user_role', 'u.created_at as user_created_at')
            ->addSelect('language.title as language_title', 'language.locale as language_locale')
            ->andWhere($qb->expr()->eq('u.id', ':user_id'))
            ->setParameter('user_id', $id)
        ;

        $data = $qb->execute()->fetchAssociative();

        if (false === $data) {
            throw new UserNoResultException();
        }

        $language = (new LanguageCurrent())
            ->setId(strval($data['language_id']))
            ->setTitle(strval($data['language_title']))
            ->setLocale(strval($data['language_locale']))
        ;

        $user = (new UserCurrent())
            ->setId(strval($data['user_id']))
            ->setGender(strval($data['user_gender']))
            ->setGenderReading(UserGender::getReadingChoice(strval($data['user_gender']), $this->translator))
            ->setName(strval($data['user_name']))
            ->setNameSlug(strval($data['user_name_slug']))
            ->setImageDefined(boolval($data['user_image_defined']))
            ->setRole(UserCurrent::ROLE_PREFIX.strval($data['user_role']))
            ->setCreatedAt(new \DateTime(strval($data['user_created_at'])))
            ->setLanguage($language)
        ;

        $this->languageRepository->populateUserReadingLanguages($user, LanguageCurrent::class);

        return $user;
    }

    private function createBaseQueryBuilder(QueryBuilder $qb): void
    {
        $qb->select('u.id as user_id', 'u.gender as user_gender', 'u.name as user_name', 'u.name_slug as user_name_slug', 'u.image_defined as user_image_defined', 'u.created_at as user_created_at')
            ->from('usr_user', 'u')
            ->addSelect('language.id as language_id')
            ->join('u', 'lng_language', 'language', $qb->expr()->eq('language.id', 'u.language_id'))
            ->where($qb->expr()->eq('u.status', ':user_status'))
            ->setParameter('user_status', UserStatus::ACTIVATED)
        ;
    }
}
