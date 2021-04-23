<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Doctrine\Repository;

use App\Common\Domain\Model\AbstractEntity;
use App\Common\Domain\Repository\RepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractDoctrineORMRepository extends ServiceEntityRepository implements RepositoryInterface
{
    public function persist(AbstractEntity $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    public function remove(AbstractEntity $entity): void
    {
        $this->getEntityManager()->remove($entity);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
