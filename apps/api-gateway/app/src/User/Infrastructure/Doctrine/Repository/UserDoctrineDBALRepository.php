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

        $qb->addSelect('u.created_at as user_created_at')
            ->addSelect('language.title as language_title', 'language.locale as language_locale')
            ->andWhere($qb->expr()->eq('u.id', ':user_id'))
            ->setParameter('user_id', $query->getId())
        ;

        $data = $qb->execute()->fetchAssociative();

        if (false === $data) {
            throw new UserNoResultException();
        }

        $language = new LanguageMedium(strval($data['language_id']), strval($data['language_title']), strval($data['language_locale']));

        $user = new UserFull(strval($data['user_id']), strval($data['user_gender']), UserGender::getReadingChoice(strval($data['user_gender']), $this->translator), strval($data['user_name']), strval($data['user_name_slug']), boolval($data['user_image_defined']), $language, new \DateTime(strval($data['user_created_at'])));

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

        $language = new Language(strval($data['language_id']));

        $user = new UserUpdate(strval($data['user_id']), strval($data['user_gender']), strval($data['user_name']), strval($data['user_email']), boolval($data['user_image_defined']), $language);

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

        $language = new LanguageCurrent(strval($data['language_id']), strval($data['language_title']), strval($data['language_locale']));

        $user = new UserCurrent(strval($data['user_id']), strval($data['user_gender']), UserGender::getReadingChoice(strval($data['user_gender']), $this->translator), strval($data['user_name']), strval($data['user_name_slug']), boolval($data['user_image_defined']), UserCurrent::ROLE_PREFIX.strval($data['user_role']), $language, new \DateTime(strval($data['user_created_at'])));

        $this->languageRepository->populateUserReadingLanguages($user, LanguageCurrent::class);

        return $user;
    }

    private function createBaseQueryBuilder(QueryBuilder $qb): void
    {
        $qb->select('u.id as user_id', 'u.gender as user_gender', 'u.name as user_name', 'u.name_slug as user_name_slug', 'u.image_defined as user_image_defined')
            ->from('usr_user', 'u')
            ->addSelect('language.id as language_id')
            ->join('u', 'lng_language', 'language', $qb->expr()->eq('language.id', 'u.language_id'))
            ->where($qb->expr()->eq('u.status', ':user_status'))
            ->setParameter('user_status', UserStatus::ACTIVATED)
        ;
    }
}
