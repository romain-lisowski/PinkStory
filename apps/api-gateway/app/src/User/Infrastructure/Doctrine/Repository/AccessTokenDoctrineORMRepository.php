<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Repository;

use App\Common\Domain\Repository\NoResultException as DomainNoResultException;
use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineORMRepository;
use App\User\Domain\Model\AccessToken;
use App\User\Domain\Repository\AccessTokenRepositoryInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

final class AccessTokenDoctrineORMRepository extends AbstractDoctrineORMRepository implements AccessTokenRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessToken::class);
    }

    public function findOne(string $id): AccessToken
    {
        try {
            $qb = $this->createQueryBuilder('accessToken');

            $qb->where($qb->expr()->eq('accessToken.id', ':accessToken_id'))
                ->setParameter('accessToken_id', $id)
            ;

            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new DomainNoResultException($e);
        }
    }
}
