<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineORMRepository;
use App\User\Domain\Model\User;
use App\User\Domain\Model\UserStatus;
use App\User\Domain\Repository\UserNoResultException;
use App\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

final class UserDoctrineORMRepository extends AbstractDoctrineORMRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOne(string $id): User
    {
        try {
            $qb = $this->createQueryBuilder('user');

            $qb->where($qb->expr()->andX(
                $qb->expr()->eq('user.id', ':user_id'),
                $qb->expr()->eq('user.status', ':user_status')
            ))
                ->setParameter('user_id', $id)
                ->setParameter('user_status', UserStatus::ACTIVATED)
            ;

            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new UserNoResultException($e);
        }
    }

    public function findOneByEmail(string $email): User
    {
        try {
            $qb = $this->createQueryBuilder('user');

            $qb->where($qb->expr()->andX(
                $qb->expr()->eq('user.email', ':user_email'),
                $qb->expr()->eq('user.status', ':user_status')
            ))
                ->setParameter('user_email', $email)
                ->setParameter('user_status', UserStatus::ACTIVATED)
            ;

            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new UserNoResultException($e);
        }
    }

    public function findOneByActivePasswordForgottenSecret(string $secret): User
    {
        try {
            $qb = $this->createQueryBuilder('user');

            $qb->where($qb->expr()->andX(
                $qb->expr()->eq('user.passwordForgottenSecret', ':user_password_forgotten_secret'),
                $qb->expr()->eq('user.passwordForgottenSecretUsed', ':user_password_forgotten_secret_used'),
                $qb->expr()->gt('user.passwordForgottenSecretCreatedAt', ':user_password_forgotten_secret_created_at'),
                $qb->expr()->eq('user.status', ':user_status')
            ))
                ->setParameter('user_password_forgotten_secret', $secret)
                ->setParameter('user_password_forgotten_secret_used', false)
                ->setParameter('user_password_forgotten_secret_created_at', (new \DateTime())->modify('-1 hour'))
                ->setParameter('user_status', UserStatus::ACTIVATED)
            ;

            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new UserNoResultException($e);
        }
    }
}
