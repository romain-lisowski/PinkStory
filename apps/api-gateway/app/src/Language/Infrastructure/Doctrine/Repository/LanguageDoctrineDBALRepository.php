<?php

declare(strict_types=1);

namespace App\Language\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineDBALRepository;
use App\Language\Query\Model\Language;
use App\Language\Query\Query\LanguageSearchQuery;
use App\Language\Query\Repository\LanguageRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

final class LanguageDoctrineDBALRepository extends AbstractDoctrineDBALRepository implements LanguageRepositoryInterface
{
    public function search(LanguageSearchQuery $query): \Traversable
    {
        $qb = $this->createQueryBuilder()
            ->select('id', 'title', 'locale')
            ->from('lng_language')
        ;

        $qb->orderBy('locale', Criteria::ASC);

        $languageDatas = $qb->execute()->fetchAllAssociative();

        $languages = new ArrayCollection();

        foreach ($languageDatas as $languageData) {
            $language = new Language(strval($languageData['id']), strval($languageData['title']), strval($languageData['locale']));
            $languages->add($language);
        }

        return $languages;
    }
}
