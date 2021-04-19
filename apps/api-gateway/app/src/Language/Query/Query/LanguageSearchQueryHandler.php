<?php

declare(strict_types=1);

namespace App\Language\Query\Query;

use App\Common\Domain\Validator\ValidatorInterface;
use App\Common\Query\Query\QueryHandlerInterface;
use App\Language\Query\Repository\LanguageRepositoryInterface;

final class LanguageSearchQueryHandler implements QueryHandlerInterface
{
    private LanguageRepositoryInterface $languageRepository;
    private ValidatorInterface $validator;

    public function __construct(LanguageRepositoryInterface $languageRepository, ValidatorInterface $validator)
    {
        $this->languageRepository = $languageRepository;
        $this->validator = $validator;
    }

    public function __invoke(LanguageSearchQuery $query): \Traversable
    {
        $this->validator->validate($query);

        return $this->languageRepository->search($query);
    }
}
