<?php

declare(strict_types=1);

namespace App\User\Repository\Dto;

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

    public function findOne(string $id): UserFull
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('id', 'name', 'name_slug', 'email', 'image_defined')
            ->from('usr_user')
        ;

        $qb->where($qb->expr()->in('id', ':user_id'))
            ->setParameter('user_id', $id)
        ;

        $userData = $qb->execute()->fetch();

        if (false === $userData) {
            throw new NoResultException();
        }

        return new UserFull(strval($userData['id']), strval($userData['name']), strval($userData['name_slug']), strval($userData['email']), boolval($userData['image_defined']));
    }
}
