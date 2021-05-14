<?php

declare(strict_types=1);

namespace App\Language\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineDBALRepository;
use App\Language\Query\Model\Language;
use App\Language\Query\Model\LanguageCurrent;
use App\Language\Query\Model\LanguageFull;
use App\Language\Query\Model\LanguageMedium;
use App\Language\Query\Query\LanguageSearchQuery;
use App\Language\Query\Repository\LanguageRepositoryInterface;
use App\User\Query\Model\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Query\QueryBuilder;

final class LanguageDoctrineDBALRepository extends AbstractDoctrineDBALRepository implements LanguageRepositoryInterface
{
    public function findBySearch(LanguageSearchQuery $query): Collection
    {
        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, LanguageFull::class);

        $qb->orderBy('language.locale', Criteria::ASC);

        $datas = $qb->execute()->fetchAllAssociative();

        $languages = new ArrayCollection();

        foreach ($datas as $data) {
            $language = (new LanguageFull())
                ->setId(strval($data['language_id']))
                ->setTitle(strval($data['language_title']))
                ->setLocale(strval($data['language_locale']))
            ;

            $languages->add($language);
        }

        return $languages;
    }

    public function findOneByLocaleForCurrent(string $locale): ?LanguageCurrent
    {
        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, LanguageCurrent::class);

        $qb->where($qb->expr()->eq('language.locale', ':locale'))
            ->setParameter('locale', $locale)
        ;

        $data = $qb->execute()->fetchAssociative();

        if (false === $data) {
            return null;
        }

        return (new LanguageCurrent())
            ->setId(strval($data['language_id']))
            ->setTitle(strval($data['language_title']))
            ->setLocale(strval($data['language_locale']))
        ;
    }

    public function findOneByAccessTokenForCurrent(string $accessTokenId): ?LanguageCurrent
    {
        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, LanguageCurrent::class);

        $qb->join('language', 'usr_user', 'u', $qb->expr()->eq('u.language_id', 'language.id'))
            ->join('u', 'usr_access_token', 'accessToken', $qb->expr()->eq('accessToken.user_id', 'u.id'))
        ;

        $qb->where($qb->expr()->eq('accessToken.id', ':access_token_id'))
            ->setParameter('access_token_id', $accessTokenId)
        ;

        $data = $qb->execute()->fetchAssociative();

        if (false === $data) {
            return null;
        }

        return (new LanguageCurrent())
            ->setId(strval($data['language_id']))
            ->setTitle(strval($data['language_title']))
            ->setLocale(strval($data['language_locale']))
        ;
    }

    public function populateUserReadingLanguages(User $user, string $languageClass = Language::class): void
    {
        $qb = $this->createQueryBuilder();

        $this->createBaseQueryBuilder($qb, $languageClass);

        $qb->join('language', 'usr_user_has_reading_language', 'userHasReadingLanguage', $qb->expr()->and(
            $qb->expr()->eq('userHasReadingLanguage.language_id', 'language.id'),
            $qb->expr()->eq('userHasReadingLanguage.user_id', ':user_id')
        ))
            ->setParameter('user_id', $user->getId())
        ;

        $qb->orderBy('language.locale', Criteria::ASC);

        $datas = $qb->execute()->fetchAllAssociative();

        foreach ($datas as $data) {
            $language = (new $languageClass())
                ->setId(strval($data['language_id']))
            ;

            if (true === in_array($languageClass, [LanguageMedium::class, LanguageFull::class, LanguageCurrent::class])) {
                $language->setTitle(strval($data['language_title']))
                    ->setLocale(strval($data['language_locale']))
                ;
            }

            $user->addReadingLanguage($language);
        }
    }

    private function createBaseQueryBuilder(QueryBuilder $qb, string $languageClass = Language::class): void
    {
        $qb->select('language.id as language_id')
            ->from('lng_language', 'language')
        ;

        if (true === in_array($languageClass, [LanguageMedium::class, LanguageFull::class, LanguageCurrent::class])) {
            $qb->addSelect('language.title as language_title', 'language.locale as language_locale');
        }
    }
}
