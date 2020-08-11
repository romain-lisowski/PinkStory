<?php

declare(strict_types=1);

namespace App\User\Doctrine\Repository;

use App\User\Entity\User;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function doesEmailAlreadyExist(string $email): bool
    {
        return false;
    }
}
