<?php

declare(strict_types=1);

namespace App\Story\Query;

use App\Query\AbstractQueryHandler;
use App\Story\Repository\Dto\StoryThemeRepositoryInterface;
use App\Validator\ValidatorManagerInterface;
use Doctrine\Common\Collections\Collection;

final class StoryThemeSearchQueryHandler extends AbstractQueryHandler
{
    private StoryThemeRepositoryInterface $storyThemeRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(StoryThemeRepositoryInterface $storyThemeRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->storyThemeRepository = $storyThemeRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): Collection
    {
        $this->validatorManager->validate($this->query);

        return $this->storyThemeRepository->search($this->query);
    }
}
