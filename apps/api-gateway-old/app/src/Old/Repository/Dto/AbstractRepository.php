<?php

declare(strict_types=1);

namespace App\Repository\Dto;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}
