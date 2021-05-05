<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Doctrine\Repository;

use App\Common\Query\Repository\RepositoryInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractDoctrineDBALRepository implements RepositoryInterface
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function createQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->getConnection()->createQueryBuilder();
    }
}
