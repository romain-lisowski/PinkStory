<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandHandler;
use App\File\ImageManagerInterface;
use App\User\Entity\User;
use App\User\Message\UserCreateMessage;
use App\Validator\ValidatorException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserCreateCommandHandler extends AbstractCommandHandler
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private UserPasswordEncoderInterface $passwordEncoder;
    private ValidatorInterface $validator;
    private ImageManagerInterface $imageManager;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator, ImageManagerInterface $imageManager)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
        $this->imageManager = $imageManager;
    }

    public function handle(): void
    {
        $errors = $this->validator->validate($this->command);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $user = new User($this->command->name, $this->command->email);
        $user->updatePassword($this->passwordEncoder->encodePassword($user, $this->command->password));

        if (null !== $this->command->image) {
            $isUploaded = $this->imageManager->upload($this->command->image, $user);
            $user->setImageDefined($isUploaded);
        }

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $this->imageManager->remove($user);

            throw new ValidatorException($errors);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->bus->dispatch(new UserCreateMessage($user->getId()));
    }
}
