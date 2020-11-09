<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Exception\ValidatorException;
use App\User\Entity\User;
use App\User\Message\UserSignupMessage;
use App\User\Upload\UserProfilePictureUploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserSignupCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private UserPasswordEncoderInterface $passwordEncoder;
    private ValidatorInterface $validator;
    private UserProfilePictureUploaderInterface $userProfilePictureUploader;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator, UserProfilePictureUploaderInterface $userProfilePictureUploader)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
        $this->userProfilePictureUploader = $userProfilePictureUploader;
    }

    public function handle(UserSignupCommand $command): void
    {
        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $user = new User();
        $user->rename($command->name)
            ->updateEmail($command->email)
            ->updatePassword($this->passwordEncoder->encodePassword($user, $command->password))
        ;

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $this->entityManager->persist($user);

        $this->userProfilePictureUploader->setUser($user);

        if (true === $this->userProfilePictureUploader->upload($command->profilePicture)) {
            $user->setProfilePictureDefined(true);
        }

        $this->entityManager->flush();

        $this->bus->dispatch(new UserSignupMessage($user->getId()));
    }
}
