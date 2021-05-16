<?php

declare(strict_types=1);

namespace App\Story\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineORMRepository;
use App\Story\Domain\Model\StoryThemeTranslation;
use Doctrine\Persistence\ManagerRegistry;

final class StoryThemeTranslationDoctrineORMRepository extends AbstractDoctrineORMRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryThemeTranslation::class);
    }
}
