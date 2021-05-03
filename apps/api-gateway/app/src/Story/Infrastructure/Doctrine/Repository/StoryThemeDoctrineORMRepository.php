<?php

declare(strict_types=1);

namespace App\Story\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineORMRepository;
use App\Story\Domain\Model\StoryTheme;
use Doctrine\Persistence\ManagerRegistry;

final class StoryThemeDoctrineORMRepository extends AbstractDoctrineORMRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryTheme::class);
    }
}
