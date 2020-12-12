<?php

declare(strict_types=1);

namespace App\Repository\Dto;

use Doctrine\ORM\EntityManagerInterface;

interface RepositoryInterface
{
    public function getEntityManager(): EntityManagerInterface;
}
