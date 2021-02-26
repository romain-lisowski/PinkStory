<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ValidatorInterface;
use App\User\Domain\Event\UserUpdatedPasswordEvent;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Security\UserPasswordEncoderInterface;

final class UserUpdatePasswordCommandHandler implements CommandHandlerInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private EventBusInterface $eventBus;
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, EventBusInterface $eventBus, UserPasswordEncoderInterface $passwordEncoder, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->eventBus = $eventBus;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(UserUpdatePasswordCommand $command): void
    {
        $user = $this->userRepository->findOne($command->getId());

        $this->authorizationChecker->isGranted(EditableInterface::UPDATE, $user);

        $user->updatePassword($command->getPassword(), $this->passwordEncoder);

        $this->validator->validate($user);

        $this->userRepository->flush();

        $this->eventBus->dispatch(new UserUpdatedPasswordEvent(
            $user->getId(),
            $user->getPassword()
        ));
    }
}
