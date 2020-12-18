<?php

declare(strict_types=1);

namespace App\Story\Query;

use App\Query\AbstractQueryHandler;
use App\Story\Repository\Dto\StoryImageRepositoryInterface;
use App\Validator\ValidatorManagerInterface;

final class StoryImageSearchQueryHandler extends AbstractQueryHandler
{
    private StoryImageRepositoryInterface $storyImageRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(StoryImageRepositoryInterface $storyImageRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->storyImageRepository = $storyImageRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): array
    {
        $this->validatorManager->validate($this->query);

        return [
            'story-images-total' => $this->storyImageRepository->countBySearch($this->query),
            'story-images' => $this->storyImageRepository->getBySearch($this->query),
        ];
    }
}
