<?php

declare(strict_types=1);

namespace App\Language\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineORMRepository;
use App\Language\Domain\Model\Language;
use App\Language\Domain\Repository\LanguageNoResultException;
use App\Language\Domain\Repository\LanguageRepositoryInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

final class LanguageDoctrineORMRepository extends AbstractDoctrineORMRepository implements LanguageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Language::class);
    }

    public function findOne(string $id): Language
    {
        try {
            $qb = $this->createQueryBuilder('language');

            $qb->where($qb->expr()->eq('language.id', ':language_id'))
                ->setParameter('language_id', $id)
            ;

            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new LanguageNoResultException($e);
        }
    }
}
