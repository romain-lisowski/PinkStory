<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Doctrine\Repository;

use App\Common\Domain\Model\AbstractEntity;
use App\Common\Domain\Repository\RepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractDoctrineORMRepository implements RepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function persist(AbstractEntity $entity): void
    {
        $this->entityManager->persist($entity);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
