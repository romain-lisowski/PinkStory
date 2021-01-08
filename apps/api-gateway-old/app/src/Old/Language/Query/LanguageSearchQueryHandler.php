<?php

declare(strict_types=1);

namespace App\Language\Query;

use App\Language\Repository\Dto\LanguageRepositoryInterface;
use App\Query\AbstractQueryHandler;
use App\Validator\ValidatorManagerInterface;
use Doctrine\Common\Collections\Collection;

final class LanguageSearchQueryHandler extends AbstractQueryHandler
{
    private LanguageRepositoryInterface $languageRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(LanguageRepositoryInterface $languageRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->languageRepository = $languageRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): Collection
    {
        $this->validatorManager->validate($this->query);

        return $this->languageRepository->getBySearch($this->query);
    }
}
