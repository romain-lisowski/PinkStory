<?php

declare(strict_types=1);

namespace App\User\Doctrine\Repository;

use App\User\Entity\User;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

final class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOne(Uuid $uuid): User
    {
        $qb = $this->createQueryBuilder('user');

        $qb->where($qb->expr()->eq('user.uuid', ':user_uuid'))
            ->setParameter('user_uuid', $uuid->toRfc4122())
        ;

        return $qb->getQuery()->getSingleResult();
    }

    public function findOneByEmail(string $email): User
    {
        $qb = $this->createQueryBuilder('user');

        $qb->where($qb->expr()->eq('user.email', ':user_email'))
            ->setParameter('user_email', $email)
        ;

        return $qb->getQuery()->getSingleResult();
    }
}
