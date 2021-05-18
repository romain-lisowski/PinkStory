<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ValidatorInterface;
use App\Language\Domain\Model\Language;
use App\Language\Domain\Repository\LanguageRepositoryInterface;
use App\User\Domain\Event\UserUpdatedInformationEvent;
use App\User\Domain\Repository\UserRepositoryInterface;

final class UserUpdateInformationCommandHandler implements CommandHandlerInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private EventBusInterface $eventBus;
    private LanguageRepositoryInterface $languageRepository;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, EventBusInterface $eventBus, LanguageRepositoryInterface $languageRepository, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->eventBus = $eventBus;
        $this->languageRepository = $languageRepository;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(UserUpdateInformationCommand $command): array
    {
        $this->validator->validate($command);

        $user = $this->userRepository->findOne($command->getId());

        $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $user);

        $language = $this->languageRepository->findOne($command->getLanguageId());

        $user->updateGender($command->getGender());
        $user->updateName($command->getName());
        $user->updateLanguage($language);
        $user->updateReadingLanguages($command->getReadingLanguageIds(), $this->languageRepository);

        $this->validator->validate($user);

        $this->userRepository->flush();

        $event = (new UserUpdatedInformationEvent())
            ->setId($user->getId())
            ->setGender($user->getGender())
            ->setName($user->getName())
            ->setLanguageId($user->getLanguage()->getId())
            ->setReadingLanguageIds(Language::extractIds($user->getReadingLanguages()->toArray()))
        ;

        $this->validator->validate($event);

        $this->eventBus->dispatch($event);

        return [];
    }
}
