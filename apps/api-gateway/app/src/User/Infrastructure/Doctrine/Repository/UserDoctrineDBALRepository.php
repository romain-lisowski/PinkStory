<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineDBALRepository;
use App\Language\Query\Model\Language;
use App\User\Domain\Repository\UserNoResultException;
use App\User\Query\Model\UserForUpdate;
use App\User\Query\Query\UserGetForUpdateQuery;
use App\User\Query\Repository\UserRepositoryInterface;

final class UserDoctrineDBALRepository extends AbstractDoctrineDBALRepository implements UserRepositoryInterface
{
    public function findOneForUpdate(UserGetForUpdateQuery $query): UserForUpdate
    {
        $qb = $this->createQueryBuilder();

        $qb->select('u.id as user_id', 'u.gender as user_gender', 'u.name as user_name', 'u.email as user_email')
            ->from('usr_user', 'u')
            ->addSelect('language.id as language_id')
            ->join('u', 'lng_language', 'language', $qb->expr()->eq('language.id', 'u.language_id'))
        ;

        $qb->where($qb->expr()->eq('u.id', ':user_id'))
            ->setParameter('user_id', $query->getId())
        ;

        $data = $qb->execute()->fetchAssociative();

        if (false === $data) {
            throw new UserNoResultException();
        }

        $language = new Language(strval($data['language_id']));

        return new UserForUpdate(strval($data['user_id']), strval($data['user_gender']), strval($data['user_name']), strval($data['user_email']), $language);
    }
}
