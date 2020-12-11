<?php

declare(strict_types=1);

namespace App\Story\Query;

use App\Query\AbstractQueryHandler;
use App\Story\Dto\StoryImageRepositoryInterface;
use App\Validator\ValidatorManagerInterface;
use Doctrine\Common\Collections\Collection;

final class StoryImageSearchQueryHandler extends AbstractQueryHandler
{
    private StoryImageRepositoryInterface $storyImageRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(StoryImageRepositoryInterface $storyImageRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->storyImageRepository = $storyImageRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): Collection
    {
        $this->validatorManager->validate($this->query);

        return $this->storyImageRepository->search($this->query);
    }
}
