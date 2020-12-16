<?php

declare(strict_types=1);

namespace App\User\Repository\Dto;

use App\Language\Model\Dto\CurrentLanguage;
use App\Language\Model\Dto\LanguageMedium;
use App\Repository\Dto\AbstractRepository;
use App\User\Model\Dto\CurrentUser;
use App\User\Model\Dto\UserFull;
use DateTime;
use Doctrine\ORM\NoResultException;

final class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function findCurrent(string $id): CurrentUser
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $qb->select('u.id as user_id', 'u.image_defined as user_image_defined', 'u.secret as user_secret', 'u.role as user_role')
            ->from('usr_user', 'u')
            ->addSelect('language.id as language_id', 'language.title as language_title', 'language.locale as language_locale')
            ->join('u', 'lng_language', 'language', $qb->expr()->eq('language.id', 'u.language_id'))
        ;

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('u.id', ':user_id'),
            $qb->expr()->eq('u.activated', ':user_activated')
        ))
            ->setParameter('user_id', $id)
            ->setParameter('user_activated', true)
        ;

        $data = $qb->execute()->fetch();

        if (false === $data) {
            throw new NoResultException();
        }

        $currentLanguage = new CurrentLanguage(strval($data['language_id']), strval($data['language_title']), strval($data['language_locale']));

        return new CurrentUser(strval($data['user_id']), boolval($data['user_image_defined']), strval($data['user_secret']), strval($data['user_role']), $currentLanguage);
    }

    public function findOne(string $id): UserFull
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $qb->select('u.id as user_id', 'u.image_defined as user_image_defined', 'u.name as user_name', 'u.name_slug as user_name_slug', 'u.email as user_email', 'u.created_at as user_created_at')
            ->from('usr_user', 'u')
            ->addSelect('language.id as language_id')
            ->join('u', 'lng_language', 'language', $qb->expr()->eq('language.id', 'u.language_id'))
        ;

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('u.id', ':user_id'),
            $qb->expr()->eq('u.activated', ':user_activated')
        ))
            ->setParameter('user_id', $id)
            ->setParameter('user_activated', true)
        ;

        $data = $qb->execute()->fetch();

        if (false === $data) {
            throw new NoResultException();
        }

        $language = new LanguageMedium(strval($data['language_id']));

        return new UserFull(strval($data['user_id']), boolval($data['user_image_defined']), strval($data['user_name']), strval($data['user_name_slug']), strval($data['user_email']), new DateTime($data['user_created_at']), $language);
    }
}
