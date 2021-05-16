<?php

declare(strict_types=1);

namespace App\Story\Query\Query;

use App\Common\Domain\Validator\ValidatorInterface;
use App\Common\Query\Query\QueryHandlerInterface;
use App\Story\Query\Repository\StoryRepositoryInterface;

final class StorySearchQueryHandler implements QueryHandlerInterface
{
    private StoryRepositoryInterface $storyRepository;
    private ValidatorInterface $validator;

    public function __construct(StoryRepositoryInterface $storyRepository, ValidatorInterface $validator)
    {
        $this->storyRepository = $storyRepository;
        $this->validator = $validator;
    }

    public function __invoke(StorySearchQuery $query): array
    {
        $this->validator->validate($query);

        return [
            'stories_total' => $this->storyRepository->countBySearch($query),
            'stories' => $this->storyRepository->findBySearch($query),
        ];
    }
}
