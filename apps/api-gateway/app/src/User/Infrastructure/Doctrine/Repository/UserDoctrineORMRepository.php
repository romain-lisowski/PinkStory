<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineORMRepository;
use App\User\Domain\Model\User;
use App\User\Domain\Model\UserStatus;
use App\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

final class UserDoctrineORMRepository extends AbstractDoctrineORMRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOne(string $id): User
    {
        $qb = $this->createQueryBuilder('user');

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('user.id', ':user_id'),
            $qb->expr()->eq('user.status', ':user_status')
        ))
            ->setParameter('user_id', $id)
            ->setParameter('user_status', UserStatus::ACTIVATED)
        ;

        return $qb->getQuery()->getSingleResult();
    }

    public function findOneByEmail(string $email): User
    {
        $qb = $this->createQueryBuilder('user');

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('user.email', ':user_email'),
            $qb->expr()->eq('user.status', ':user_status')
        ))
            ->setParameter('user_email', $email)
            ->setParameter('user_status', UserStatus::ACTIVATED)
        ;

        return $qb->getQuery()->getSingleResult();
    }
}
