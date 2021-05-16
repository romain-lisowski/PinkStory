<?php

declare(strict_types=1);

namespace App\Story\Query\Query;

use App\Common\Domain\Validator\ValidatorInterface;
use App\Common\Query\Query\QueryHandlerInterface;
use App\Story\Query\Repository\StoryImageRepositoryInterface;

final class StoryImageSearchQueryHandler implements QueryHandlerInterface
{
    private StoryImageRepositoryInterface $storyImageRepository;
    private ValidatorInterface $validator;

    public function __construct(StoryImageRepositoryInterface $storyImageRepository, ValidatorInterface $validator)
    {
        $this->storyImageRepository = $storyImageRepository;
        $this->validator = $validator;
    }

    public function __invoke(StoryImageSearchQuery $query): array
    {
        $this->validator->validate($query);

        return [
            'story_images_total' => $this->storyImageRepository->countBySearch($query),
            'story_images' => $this->storyImageRepository->findBySearch($query),
        ];
    }
}
