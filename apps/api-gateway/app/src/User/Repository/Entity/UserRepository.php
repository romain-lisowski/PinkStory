<?php

declare(strict_types=1);

namespace App\User\Repository\Entity;

use App\User\Model\Entity\User;
use DateTime;
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

    public function findOneByActiveEmailValidationCode(string $code): User
    {
        $qb = $this->createQueryBuilder('user');

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('user.emailValidationCode', ':user_email_validation_code'),
            $qb->expr()->eq('user.emailValidationCodeUsed', ':user_email_validation_code_used')
        ))->setParameters([
            'user_email_validation_code' => $code,
            'user_email_validation_code_used' => false,
        ]);

        return $qb->getQuery()->getSingleResult();
    }

    public function findOneByActivePasswordForgottenSecret(string $secret): User
    {
        $qb = $this->createQueryBuilder('user');

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('user.passwordForgottenSecret', ':user_password_forgotten_secret'),
            $qb->expr()->eq('user.passwordForgottenSecretUsed', ':user_password_forgotten_secret_used'),
            $qb->expr()->gt('user.passwordForgottenSecretCreatedAt', ':user_password_forgotten_secret_created_at')
        ))->setParameters([
            'user_password_forgotten_secret' => $secret,
            'user_password_forgotten_secret_used' => false,
            'user_password_forgotten_secret_created_at' => (new DateTime())->modify('-1 hour'),
        ]);

        return $qb->getQuery()->getSingleResult();
    }
}
