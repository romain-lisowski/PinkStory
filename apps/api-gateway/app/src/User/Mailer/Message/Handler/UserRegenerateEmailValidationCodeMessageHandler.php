<?php

declare(strict_types=1);

namespace App\User\Mailer\Message\Handler;

use App\User\Message\UserRegenerateEmailValidationCodeMessage;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

final class UserRegenerateEmailValidationCodeMessageHandler implements MessageSubscriberInterface
{
    private EntityManagerInterface $entityManager;
    private UserRepository $repository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function __invoke(UserRegenerateEmailValidationCodeMessage $message)
    {
        $user = $this->repository->findOne($message->getId());

        // TODO: send mail
    }

    public static function getHandledMessages(): iterable
    {
        yield UserRegenerateEmailValidationCodeMessage::class => [
            'from_transport' => 'sync',
        ];
    }
}
