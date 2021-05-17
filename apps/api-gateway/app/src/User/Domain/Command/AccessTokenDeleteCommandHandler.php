<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ValidatorInterface;
use App\User\Domain\Event\AccessTokenDeletedEvent;
use App\User\Domain\Repository\AccessTokenRepositoryInterface;

final class AccessTokenDeleteCommandHandler implements CommandHandlerInterface
{
    private AccessTokenRepositoryInterface $accessTokenRepository;
    private AuthorizationCheckerInterface $authorizationChecker;
    private EventBusInterface $eventBus;
    private ValidatorInterface $validator;

    public function __construct(AccessTokenRepositoryInterface $accessTokenRepository, AuthorizationCheckerInterface $authorizationChecker, EventBusInterface $eventBus, ValidatorInterface $validator)
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->authorizationChecker = $authorizationChecker;
        $this->eventBus = $eventBus;
        $this->validator = $validator;
    }

    public function __invoke(AccessTokenDeleteCommand $command): array
    {
        $this->validator->validate($command);

        $accessToken = $this->accessTokenRepository->findOne($command->getId());

        $this->authorizationChecker->isGranted(EditableInterface::DELETE, $accessToken);

        $this->accessTokenRepository->remove($accessToken);
        $this->accessTokenRepository->flush();

        $event = (new AccessTokenDeletedEvent())
            ->setId($accessToken->getId())
        ;

        $this->validator->validate($event);

        $this->eventBus->dispatch($event);

        return [];
    }
}
