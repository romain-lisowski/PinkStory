<?php

declare(strict_types=1);

namespace App\Story\Query;

use App\Query\AbstractQueryHandler;
use App\Story\Repository\Dto\StoryRepositoryInterface;
use App\Validator\ValidatorManagerInterface;

final class StorySearchQueryHandler extends AbstractQueryHandler
{
    private StoryRepositoryInterface $storyRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(StoryRepositoryInterface $storyRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->storyRepository = $storyRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): array
    {
        $this->validatorManager->validate($this->query);

        return [
            'stories_total' => $this->storyRepository->countBySearch($this->query),
            'stories' => $this->storyRepository->getBySearch($this->query),
        ];
    }
}
