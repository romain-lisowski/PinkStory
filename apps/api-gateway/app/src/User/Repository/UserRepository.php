<?php

declare(strict_types=1);

namespace App\User\Repository;

use App\User\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOne(string $id): User
    {
        $qb = $this->createQueryBuilder('user');

        $qb->where($qb->expr()->eq('user.id', ':user_id'))
            ->setParameter('user_id', $id)
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

    public function findOneByNotUsedEmailValidationSecret(string $secret): User
    {
        $qb = $this->createQueryBuilder('user');

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('user.emailValidationSecret', ':user_secret'),
            $qb->expr()->eq('user.emailValidationSecretUsed', ':user_secret_used')
        ))->setParameters([
            'user_secret' => $secret,
            'user_secret_used' => false,
        ]);

        return $qb->getQuery()->getSingleResult();
    }
}
