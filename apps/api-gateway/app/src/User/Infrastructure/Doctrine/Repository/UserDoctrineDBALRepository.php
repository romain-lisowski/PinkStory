<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Repository;

use App\Common\Domain\Translation\TranslatorInterface;
use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineDBALRepository;
use App\Language\Query\Model\Language;
use App\Language\Query\Model\LanguageMedium;
use App\User\Domain\Model\UserGender;
use App\User\Domain\Model\UserStatus;
use App\User\Domain\Repository\UserNoResultException;
use App\User\Query\Model\UserForUpdate;
use App\User\Query\Model\UserFull;
use App\User\Query\Query\UserGetForUpdateQuery;
use App\User\Query\Query\UserGetQuery;
use App\User\Query\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class UserDoctrineDBALRepository extends AbstractDoctrineDBALRepository implements UserRepositoryInterface
{
    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        parent::__construct($entityManager);

        $this->translator = $translator;
    }

    public function findOne(UserGetQuery $query): UserFull
    {
        $qb = $this->createQueryBuilder();

        $qb->select('u.id as user_id', 'u.gender as user_gender', 'u.name as user_name', 'u.name_slug as user_name_slug', 'u.image_defined as user_image_defined', 'u.created_at as user_created_at')
            ->from('usr_user', 'u')
            ->addSelect('language.id as language_id', 'language.title as language_title', 'language.locale as language_locale')
            ->join('u', 'lng_language', 'language', $qb->expr()->eq('language.id', 'u.language_id'))
        ;

        $qb->where($qb->expr()->and(
            $qb->expr()->eq('u.id', ':user_id'),
            $qb->expr()->eq('u.status', ':user_status')
        ))->setParameters([
            'user_id' => $query->getId(),
            'user_status' => UserStatus::ACTIVATED,
        ]);

        $data = $qb->execute()->fetchAssociative();

        if (false === $data) {
            throw new UserNoResultException();
        }

        $language = new LanguageMedium(strval($data['language_id']), strval($data['language_title']), strval($data['language_locale']));

        return new UserFull(strval($data['user_id']), strval($data['user_gender']), UserGender::getReadingChoice(strval($data['user_gender']), $this->translator), strval($data['user_name']), strval($data['user_name_slug']), boolval($data['user_image_defined']), $language, new \DateTime(strval($data['user_image_defined'])));
    }

    public function findOneForUpdate(UserGetForUpdateQuery $query): UserForUpdate
    {
        $qb = $this->createQueryBuilder();

        $qb->select('u.id as user_id', 'u.gender as user_gender', 'u.name as user_name', 'u.email as user_email', 'u.image_defined as user_image_defined')
            ->from('usr_user', 'u')
            ->addSelect('language.id as language_id')
            ->join('u', 'lng_language', 'language', $qb->expr()->eq('language.id', 'u.language_id'))
        ;

        $qb->where($qb->expr()->and(
            $qb->expr()->eq('u.id', ':user_id'),
            $qb->expr()->eq('u.status', ':user_status')
        ))->setParameters([
            'user_id' => $query->getId(),
            'user_status' => UserStatus::ACTIVATED,
        ]);

        $data = $qb->execute()->fetchAssociative();

        if (false === $data) {
            throw new UserNoResultException();
        }

        $language = new Language(strval($data['language_id']));

        return new UserForUpdate(strval($data['user_id']), strval($data['user_gender']), strval($data['user_name']), strval($data['user_email']), boolval($data['user_image_defined']), $language);
    }
}
