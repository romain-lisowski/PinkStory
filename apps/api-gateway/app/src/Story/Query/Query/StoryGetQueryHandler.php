<?php

declare(strict_types=1);

namespace App\Story\Query\Query;

use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ConstraintViolation;
use App\Common\Domain\Validator\ValidationFailedException;
use App\Common\Domain\Validator\ValidatorInterface;
use App\Common\Query\Query\QueryHandlerInterface;
use App\Story\Query\Repository\StoryRatingRepositoryInterface;
use App\Story\Query\Repository\StoryRepositoryInterface;
use App\User\Domain\Repository\UserNoResultException;
use App\User\Query\Repository\UserRepositoryInterface;

final class StoryGetQueryHandler implements QueryHandlerInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private StoryRepositoryInterface $storyRepository;
    private StoryRatingRepositoryInterface $storyRatingRepository;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, StoryRepositoryInterface $storyRepository, StoryRatingRepositoryInterface $storyRatingRepository, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->storyRepository = $storyRepository;
        $this->storyRatingRepository = $storyRatingRepository;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(StoryGetQuery $query): array
    {
        try {
            $this->validator->validate($query);

            $story = $this->storyRepository->findOne($query);

            $storyRating = null;

            if (null !== $query->getUserId()) {
                $user = $this->userRepository->findOneLite($query->getUserId());

                $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $user);

                $storyRating = $this->storyRatingRepository->findOneForUpdate($story->getId(), $user->getId());

                if (null !== $storyRating) {
                    $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $storyRating);
                }
            }

            return [
                'story' => $story,
                'current_story_rating' => $storyRating,
            ];
        } catch (UserNoResultException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('user_id', 'user.validator.constraint.user_not_found'),
            ]);
        }
    }
}
