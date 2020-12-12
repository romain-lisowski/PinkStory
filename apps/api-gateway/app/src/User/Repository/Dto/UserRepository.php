<?php

declare(strict_types=1);

namespace App\User\Repository\Dto;

use App\Language\Model\Dto\CurrentLanguage;
use App\User\Model\Dto\CurrentUser;
use App\User\Model\Dto\UserFull;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

final class UserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findCurrent(string $id): CurrentUser
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('u.id as user_id', 'u.image_defined as user_image_defined', 'u.secret as user_secret', 'u.role as user_role', 'language.id as language_id', 'language.title as language_title', 'language.locale as language_locale')
            ->from('usr_user', 'u')
            ->join('u', 'lng_language', 'language', 'u.language_id = language.id')
        ;

        $qb->where($qb->expr()->eq('u.id', ':user_id'))
            ->setParameter('user_id', $id)
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
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('id', 'image_defined', 'name', 'name_slug', 'email')
            ->from('usr_user')
        ;

        $qb->where($qb->expr()->eq('id', ':user_id'))
            ->setParameter('user_id', $id)
        ;

        $data = $qb->execute()->fetch();

        if (false === $data) {
            throw new NoResultException();
        }

        return new UserFull(strval($data['id']), boolval($data['image_defined']), strval($data['name']), strval($data['name_slug']), strval($data['email']));
    }
}
