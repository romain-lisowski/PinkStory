<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ConstraintViolation;
use App\Common\Domain\Validator\ValidationFailedException;
use App\Common\Domain\Validator\ValidatorInterface;
use App\Language\Domain\Repository\LanguageNoResultException;
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

    public function __invoke(UserUpdateInformationCommand $command): void
    {
        try {
            $this->validator->validate($command);

            $user = $this->userRepository->findOne($command->getId());

            $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $user);

            $language = $this->languageRepository->findOne($command->getLanguageId());

            $user->updateGender($command->getGender());
            $user->updateName($command->getName());
            $user->updateLanguage($language);

            $this->validator->validate($user);

            $this->userRepository->flush();

            $this->eventBus->dispatch(new UserUpdatedInformationEvent(
                $user->getId(),
                $user->getGender(),
                $user->getName(),
                $user->getLanguage()->getId()
            ));
        } catch (LanguageNoResultException $e) {
            throw new ValidationFailedException([
                new ConstraintViolation('language_id', 'language.validator.constraint.language_not_found'),
            ]);
        }
    }
}
