<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineDBALRepository;
use App\User\Query\Model\AccessToken;
use App\User\Query\Model\User;
use App\User\Query\Query\AccessTokenSearchQuery;
use App\User\Query\Repository\AccessTokenRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

final class AccessTokenDoctrineDBALRepository extends AbstractDoctrineDBALRepository implements AccessTokenRepositoryInterface
{
    public function findBySearch(AccessTokenSearchQuery $query): Collection
    {
        $qb = $this->createQueryBuilder();

        $qb->select('id', 'user_id')
            ->from('usr_access_token')
        ;

        $qb->where($qb->expr()->eq('user_id', ':user_id'))
            ->setParameter('user_id', $query->getUserId())
        ;

        $qb->orderBy('last_updated_at', Criteria::DESC);

        $datas = $qb->execute()->fetchAllAssociative();

        $accessTokens = new ArrayCollection();

        foreach ($datas as $data) {
            $accessToken = new AccessToken(strval($data['id']), new User(strval($data['user_id'])));
            $accessTokens->add($accessToken);
        }

        return $accessTokens;
    }
}
