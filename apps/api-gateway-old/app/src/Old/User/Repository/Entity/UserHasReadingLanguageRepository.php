<?php

declare(strict_types=1);

namespace App\User\Repository\Entity;

use App\User\Model\Entity\UserHasReadingLanguage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class UserHasReadingLanguageRepository extends ServiceEntityRepository implements UserHasReadingLanguageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserHasReadingLanguage::class);
    }
}
