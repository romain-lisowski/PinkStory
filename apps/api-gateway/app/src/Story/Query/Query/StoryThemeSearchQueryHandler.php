<?php

declare(strict_types=1);

namespace App\Story\Query\Query;

use App\Common\Domain\Validator\ValidatorInterface;
use App\Common\Query\Query\QueryHandlerInterface;
use App\Story\Query\Repository\StoryThemeRepositoryInterface;

final class StoryThemeSearchQueryHandler implements QueryHandlerInterface
{
    private StoryThemeRepositoryInterface $storyThemeRepository;
    private ValidatorInterface $validator;

    public function __construct(StoryThemeRepositoryInterface $storyThemeRepository, ValidatorInterface $validator)
    {
        $this->storyThemeRepository = $storyThemeRepository;
        $this->validator = $validator;
    }

    public function __invoke(StoryThemeSearchQuery $query): array
    {
        $this->validator->validate($query);

        return [
            'story-themes' => $this->storyThemeRepository->findBySearch($query),
        ];
    }
}
