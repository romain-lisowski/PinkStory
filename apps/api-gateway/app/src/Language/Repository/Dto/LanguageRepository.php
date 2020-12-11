<?php

declare(strict_types=1);

namespace App\Language\Repository\Dto;

use App\Language\Model\Dto\LanguageFull;
use App\Language\Query\LanguageSearchQuery;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

final class LanguageRepository implements LanguageRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function search(LanguageSearchQuery $query): Collection
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('id', 'title', 'locale')
            ->from('lng_language')
        ;

        $languageDatas = $qb->execute()->fetchAll();

        $languages = new ArrayCollection();

        foreach ($languageDatas as $languageData) {
            $language = new LanguageFull(strval($languageData['id']), strval($languageData['title']), strval($languageData['locale']));
            $languages->add($language);
        }

        return $languages;
    }
}