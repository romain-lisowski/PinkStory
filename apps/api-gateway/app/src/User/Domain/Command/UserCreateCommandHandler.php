<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandHandlerInterface;
use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\File\ImageManagerInterface;
use App\Common\Domain\Validator\ValidatorInterface;
use App\Language\Domain\Model\Language;
use App\Language\Domain\Repository\LanguageRepositoryInterface;
use App\User\Domain\Event\UserCreatedEvent;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Security\UserPasswordEncoderInterface;
use App\User\Query\Model\User as QueryUser;

final class UserCreateCommandHandler implements CommandHandlerInterface
{
    private EventBusInterface $eventBus;
    private ImageManagerInterface $imageManager;
    private LanguageRepositoryInterface $languageRepository;
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(EventBusInterface $eventBus, ImageManagerInterface $imageManager, LanguageRepositoryInterface $languageRepository, UserPasswordEncoderInterface $passwordEncoder, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        $this->eventBus = $eventBus;
        $this->imageManager = $imageManager;
        $this->languageRepository = $languageRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(UserCreateCommand $command): array
    {
        $this->validator->validate($command);

        $language = $this->languageRepository->findOne($command->getLanguageId());

        $user = (new User())
            ->setGender($command->getGender())
            ->setName($command->getName())
            ->setEmail($command->getEmail())
            ->setPassword($command->getPassword(), $this->passwordEncoder)
            ->setImageDefined(null !== $command->getImage() ? true : false)
            ->setRole($command->getRole())
            ->setStatus($command->getStatus())
            ->setLanguage($language)
            ->addReadingLanguage($language)
        ;

        $this->validator->validate($user);

        if (null !== $command->getImage()) {
            $this->imageManager->upload($command->getImage(), $user);
        }

        $this->userRepository->persist($user);
        $this->userRepository->flush();

        $event = (new UserCreatedEvent())
            ->setId($user->getId())
            ->setGender($user->getGender())
            ->setName($user->getName())
            ->setEmail($user->getEmail())
            ->setEmailValidationCode($user->getEmailValidationCode())
            ->setPassword($user->getPassword())
            ->setImagePath($user->getImagePath())
            ->setRole($user->getRole())
            ->setStatus($user->getStatus())
            ->setLanguageId($user->getLanguage()->getId())
            ->setReadingLanguageIds(Language::extractIds($user->getReadingLanguages()->toArray()))
        ;

        $this->validator->validate($event);

        $this->eventBus->dispatch($event);

        return [
            'user' => (new QueryUser())->setId($user->getId()),
        ];
    }
}
