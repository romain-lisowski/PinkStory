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

    protected function isInstanceOf(string $className, array $classNamesExpected): bool
    {
        foreach ($classNamesExpected as $classNameExpected) {
            if (
                $className === $classNameExpected
                || true === is_subclass_of($className, $classNameExpected)
            ) {
                return true;
            }
        }

        return false;
    }
}
