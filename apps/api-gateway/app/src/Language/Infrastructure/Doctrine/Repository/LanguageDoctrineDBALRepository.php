<?php

declare(strict_types=1);

namespace App\Language\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineDBALRepository;
use App\Language\Query\Model\LanguageCurrent;
use App\Language\Query\Model\LanguageFull;
use App\Language\Query\Query\LanguageSearchQuery;
use App\Language\Query\Repository\LanguageRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

final class LanguageDoctrineDBALRepository extends AbstractDoctrineDBALRepository implements LanguageRepositoryInterface
{
    public function search(LanguageSearchQuery $query): \Traversable
    {
        $qb = $this->createQueryBuilder();

        $qb->select('id', 'title', 'locale')
            ->from('lng_language')
        ;

        $qb->orderBy('locale', Criteria::ASC);

        $datas = $qb->execute()->fetchAllAssociative();

        $languages = new ArrayCollection();

        foreach ($datas as $data) {
            $language = new LanguageFull(strval($data['id']), strval($data['title']), strval($data['locale']));
            $languages->add($language);
        }

        return $languages;
    }

    public function findOneByLocaleForCurrent(string $locale): ?LanguageCurrent
    {
        $qb = $this->createQueryBuilder();

        $qb->select('id', 'title', 'locale')
            ->from('lng_language')
        ;

        $qb->where($qb->expr()->eq('locale', ':locale'))
            ->setParameter('locale', $locale)
        ;

        $data = $qb->execute()->fetchAssociative();

        if (false === $data) {
            return null;
        }

        return new LanguageCurrent(strval($data['id']), strval($data['title']), strval($data['locale']));
    }
}
