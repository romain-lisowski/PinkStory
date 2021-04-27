<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineORMRepository;
use App\User\Domain\Model\UserHasReadingLanguage;
use Doctrine\Persistence\ManagerRegistry;

final class UserHasReadingLanguageDoctrineORMRepository extends AbstractDoctrineORMRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserHasReadingLanguage::class);
    }
}
