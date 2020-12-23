<?php

declare(strict_types=1);

namespace App\Story\Query;

use App\Model\EditableInterface;
use App\Query\AbstractQueryHandler;
use App\Security\AuthorizationManagerInterface;
use App\Story\Model\Dto\StoryForUpdate;
use App\Story\Repository\Dto\StoryRepositoryInterface;
use App\Validator\ValidatorManagerInterface;

final class StoryGetForUpdateQueryHandler extends AbstractQueryHandler
{
    private AuthorizationManagerInterface $authorizationManager;
    private StoryRepositoryInterface $storyRepository;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(AuthorizationManagerInterface $authorizationManager, StoryRepositoryInterface $storyRepository, ValidatorManagerInterface $validatorManager)
    {
        $this->authorizationManager = $authorizationManager;
        $this->storyRepository = $storyRepository;
        $this->validatorManager = $validatorManager;
    }

    public function handle(): StoryForUpdate
    {
        $this->validatorManager->validate($this->query);

        $story = $this->storyRepository->getOneForUpdate($this->query);

        $this->authorizationManager->isGranted(EditableInterface::UPDATE, $story);

        return $story;
    }
}
