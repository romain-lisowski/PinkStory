<?php

declare(strict_types=1);

namespace App\Story\Query\Query;

use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ValidatorInterface;
use App\Common\Query\Query\QueryHandlerInterface;
use App\Story\Query\Repository\StoryRepositoryInterface;

final class StoryGetQueryHandler implements QueryHandlerInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private StoryRepositoryInterface $storyRepository;
    private ValidatorInterface $validator;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, StoryRepositoryInterface $storyRepository, ValidatorInterface $validator)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->storyRepository = $storyRepository;
        $this->validator = $validator;
    }

    public function __invoke(StoryGetQuery $query): array
    {
        $this->validator->validate($query);

        $story = $this->storyRepository->findOne($query);

        return [
            'story' => $story,
        ];
    }
}
