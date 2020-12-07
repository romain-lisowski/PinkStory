<?php

declare(strict_types=1);

namespace App\Language\Repository;

use App\Language\Entity\Language;
use App\Language\Query\LanguageSearchQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

final class LanguageRepository extends ServiceEntityRepository implements LanguageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Language::class);
    }

    public function findOne(string $id): Language
    {
        $qb = $this->createQueryBuilder('language');

        $qb->where($qb->expr()->eq('language.id', ':language_id'))
            ->setParameter('language_id', $id)
        ;

        return $qb->getQuery()->getSingleResult();
    }

    public function findOneByLocale(string $locale): Language
    {
        $qb = $this->createQueryBuilder('language');

        $qb->where($qb->expr()->eq('language.locale', ':language_locale'))
            ->setParameter('language_locale', $locale)
        ;

        return $qb->getQuery()->getSingleResult();
    }

    public function search(LanguageSearchQuery $query): Collection
    {
        $qb = $this->createQueryBuilder('language');

        $languages = $qb->getQuery()->getResult();

        return new ArrayCollection($languages);
    }
}
