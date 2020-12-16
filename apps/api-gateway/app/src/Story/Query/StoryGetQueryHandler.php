<?php

declare(strict_types=1);

namespace App\Story\Query;

use App\Query\AbstractQueryHandler;
use App\Story\Model\Dto\StoryFull;
use App\Story\Repository\Dto\StoryRepositoryInterface;
use App\Validator\ValidatorManagerInterface;

final class StoryGetQueryHandler extends AbstractQueryHandler
{
    private StoryRepositoryInterface $storyRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(StoryRepositoryInterface $storyRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->storyRepository = $storyRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): StoryFull
    {
        $this->validatorManager->validate($this->query);

        return $this->storyRepository->getOne($this->query);
    }
}
